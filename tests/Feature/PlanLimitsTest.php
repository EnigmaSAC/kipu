<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\Fakes\FakePlanLimits;

class PlanLimitsTest extends FeatureTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function testUsersCreateShowsWarningWhenPlanLimitExceeded()
    {
        Cache::put('plans.limits', $this->planLimitsExceededData('user'));
        config(['app.env' => 'local']);

        $this->loginAs()
            ->get(route('users.create'))
            ->assertOk()
            ->assertSee('Plan limit exceeded');
    }

    public function testCompaniesCreateShowsWarningWhenPlanLimitExceeded()
    {
        Cache::put('plans.limits', $this->planLimitsExceededData('company'));
        config(['app.env' => 'local']);

        $this->loginAs()
            ->get(route('companies.create'))
            ->assertOk()
            ->assertSee('Plan limit exceeded');
    }

    public function testInvoicesCreateShowsWarningWhenPlanLimitExceeded()
    {
        Cache::put('plans.limits', $this->planLimitsExceededData('invoice'));
        config(['app.env' => 'local']);

        $this->loginAs()
            ->get(route('invoices.create'))
            ->assertOk()
            ->assertSee('Plan limit exceeded');
    }

    public function testWarningsAreClearedAfterSuccessfulFetch()
    {
        config(['app.env' => 'local']);

        app()->bind(\App\Http\ViewComposers\PlanLimits::class, FakePlanLimits::class);

        FakePlanLimits::$responses = [
            $this->planLimitsExceededData('user'),
            $this->planLimitsData(),
        ];

        $this->loginAs()
            ->get(route('users.create'))
            ->assertOk()
            ->assertSee('Plan limit exceeded');

        Cache::forget('plans.limits');

        $this->loginAs()
            ->get(route('users.create'))
            ->assertOk()
            ->assertDontSee('Plan limit exceeded');

        $this->loginAs()
            ->get(route('companies.create'))
            ->assertOk()
            ->assertDontSee('Plan limit exceeded');

        $this->loginAs()
            ->get(route('invoices.create'))
            ->assertOk()
            ->assertDontSee('Plan limit exceeded');
    }

    protected function planLimitsData(): object
    {
        return (object) [
            'user' => (object) ['action_status' => true, 'view_status' => true, 'message' => 'Success'],
            'company' => (object) ['action_status' => true, 'view_status' => true, 'message' => 'Success'],
            'invoice' => (object) ['action_status' => true, 'view_status' => true, 'message' => 'Success'],
        ];
    }

    protected function planLimitsExceededData(string $type): object
    {
        $data = $this->planLimitsData();
        $data->$type->view_status = false;
        $data->$type->message = 'Plan limit exceeded';

        return $data;
    }
}

