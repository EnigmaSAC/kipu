<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;

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
}

