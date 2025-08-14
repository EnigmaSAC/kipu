<?php

namespace Tests\Feature\Auth;

use App\Jobs\Auth\CreateUser;
use Tests\Feature\FeatureTestCase;

class UserInvitationSoftDeleteTest extends FeatureTestCase
{
    public function testInvitationSoftDeletedWhenUserDeleted(): void
    {
        $request = user_model_class()::factory()->enabled()->raw();
        $request['send_invitation'] = 1;

        $user = $this->dispatch(new CreateUser($request));

        $this->loginAs()
            ->delete(route('users.destroy', $user->id))
            ->assertOk();

        $this->assertSoftDeleted('user_invitations', ['user_id' => $user->id]);
    }
}

