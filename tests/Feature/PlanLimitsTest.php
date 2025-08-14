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

    public function testUsersCreateShowsWarningWhenPlanCheckFails()
    {
        Cache::put('plans.limits', false);
        config(['app.env' => 'local']);

        $this->loginAs()
            ->get(route('users.create'))
            ->assertOk()
            ->assertSee('Could not verify plan limit for user');
    }

    public function testCompaniesCreateShowsWarningWhenPlanCheckFails()
    {
        Cache::put('plans.limits', false);
        config(['app.env' => 'local']);

        $this->loginAs()
            ->get(route('companies.create'))
            ->assertOk()
            ->assertSee('Could not verify plan limit for company');
    }

    public function testInvoicesCreateShowsWarningWhenPlanCheckFails()
    {
        Cache::put('plans.limits', false);
        config(['app.env' => 'local']);

        $this->loginAs()
            ->get(route('invoices.create'))
            ->assertOk()
            ->assertSee('Could not verify plan limit for invoice');
    }

    public function testWarningsAreClearedAfterSuccessfulFetch()
    {
        config(['app.env' => 'local']);

        app()->bind(\App\Http\ViewComposers\PlanLimits::class, FakePlanLimits::class);

        FakePlanLimits::$responses = [false, $this->planLimitsData()];

        $this->loginAs()
            ->get(route('users.create'))
            ->assertOk()
            ->assertSee('Could not verify plan limit for user');

        $this->loginAs()
            ->get(route('users.create'))
            ->assertOk()
            ->assertDontSee('Could not verify plan limit for user');

        $this->loginAs()
            ->get(route('companies.create'))
            ->assertOk()
            ->assertDontSee('Could not verify plan limit for company');

        $this->loginAs()
            ->get(route('invoices.create'))
            ->assertOk()
            ->assertDontSee('Could not verify plan limit for invoice');
    }

    protected function planLimitsData(): object
    {
        return (object) [
            'user' => (object) ['action_status' => true, 'view_status' => true, 'message' => 'Success'],
            'company' => (object) ['action_status' => true, 'view_status' => true, 'message' => 'Success'],
            'invoice' => (object) ['action_status' => true, 'view_status' => true, 'message' => 'Success'],
        ];
    }
}

