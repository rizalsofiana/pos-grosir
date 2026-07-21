<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('role:admin')->get('/private-area', function () {
            return response()->json(['status' => 'ok']);
        });
    }

    public function test_unauthenticated_user_is_denied(): void
    {
        Auth::shouldReceive('user')->once()->andReturn(null);

        $this->get('/private-area')
            ->assertStatus(401);
    }

    public function test_user_with_matching_role_is_allowed(): void
    {
        $role = new \stdClass();
        $role->name = 'admin';

        $user = new \stdClass();
        $user->role = $role;

        Auth::shouldReceive('user')->once()->andReturn($user);

        $this->get('/private-area')
            ->assertStatus(200)
            ->assertJson(['status' => 'ok']);
    }

    public function test_user_with_different_role_is_forbidden(): void
    {
        $role = new \stdClass();
        $role->name = 'kasir';

        $user = new \stdClass();
        $user->role = $role;

        Auth::shouldReceive('user')->once()->andReturn($user);

        $this->get('/private-area')
            ->assertStatus(403);
    }
}
