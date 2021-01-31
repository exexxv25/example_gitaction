<?php

namespace Tests\Feature\Location;

use Tests\TestCase;
use App\Models\User;
use App\Models\Location;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    /**
     * @group location
     * @test
     */
    public function LocationTest()
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

        $name = Faker::create();

        $response = $this->postJson('/api/v1/location', [
            'name' => $name->name,
            'latitude' => 17.888585,
            'altitude' => 13.33333,
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'name' => true,
            ]);

        $data = json_decode($response->getContent());

        $nameAnother = Faker::create();

        $response = $this->putJson('/api/v1/location', [
            'id' => $data->id,
            'name' => $nameAnother->name,
            'latitude' => 17.888585,
            'altitude' => 13.33333,
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'name' => true,
            ]);

        $response = $this->getJson('/api/v1/location', [
            ]);

        $response
            ->assertStatus(200);


        Location::find($data->id)->delete();
    }
}
