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
        $user->refresh();
        $this->assertNotNull($user->invitation);
        $this->assertModelExists($user->invitation);

        $this->loginAs()
            ->delete(route('users.destroy', $user->id))
            ->assertOk();

        $this->assertSoftDeleted((new \App\Models\Auth\UserInvitation)->getTable(), ['user_id' => $user->id]);
    }

    public function testInvitationForceDeletedWhenUserForceDeleted(): void
    {
        $user = user_model_class()::factory()->create();

        $user->refresh();
        $this->assertNotNull($user->invitation);
        $this->assertModelExists($user->invitation);

        $user->forceDelete();

        $this->assertDatabaseMissing((new \App\Models\Auth\UserInvitation)->getTable(), ['user_id' => $user->id]);
    }
}
