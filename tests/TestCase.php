<?php

namespace Tests;

use App\Traits\Jobs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, Jobs, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.offline' => true]);

        $this->withoutMiddleware([\App\Http\Middleware\RedirectIfHitPlanLimits::class]);

        $this->artisan('db:seed', ['--class' => '\Database\Seeders\TestCompany', '--force' => true]);
    }
}
