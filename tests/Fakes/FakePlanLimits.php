<?php

namespace Tests\Fakes;

use App\Http\ViewComposers\PlanLimits as BasePlanLimits;

class FakePlanLimits extends BasePlanLimits
{
    /**
     * @var array<int, mixed>
     */
    public static array $responses = [];

    public static function getResponseData($method, $path, $data = [], $status_code = 200)
    {
        return array_shift(static::$responses);
    }
}
