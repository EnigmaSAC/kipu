<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Auth\UserInvitation as Model;
use App\Models\Auth\User;
use Illuminate\Support\Str;

class UserInvitation extends Factory
{
    protected $model = Model::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ];
    }
}
