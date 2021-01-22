<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * @group auth
     * @test
     */
    public function LogoutTest()
    {
        if (! User::where('email', 'admin@neighbors.com.ar')->exists()) {
            Artisan::call('db:seed --class=user_test');
        }

        $response = $this->postJson('/auth/login', [
            'email' => 'admin@neighbors.com.ar',
            'password' => 'neighbors3212021',
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'access_token' => true,
            ]);

        $response = $this->post('/auth/logout');

        $response
        ->assertStatus(200);
    }
}
