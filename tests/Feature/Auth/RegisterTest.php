<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    /**
     * @group auth
     * @test
     */
    public function RegisterTest()
    {
        $faker = Faker::create();

        $response = $this->postJson('/auth/register', [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'password' => 'neighbors3212021',
            'password_confirmation' => 'neighbors3212021'
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'user' => true
            ]);



        $data = json_decode($response->getContent());

        User::find($data->user->id)->delete();

    }

}
