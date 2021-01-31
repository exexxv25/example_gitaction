<?php

namespace Tests\Feature\Message;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    /**
     * @group message
     * @test
     */
    public function MessageTest()
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

        $response = $this->postJson('/api/v1/message', [
            'user_id' => 1,
            'type_id' => 1,
            'location_id' => 1,
            'subject' => "Alerta Covid",
            'body' => "Mi vecino tiene covid",
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'subject' => true,
            ]);

        $data = json_decode($response->getContent());

        $response = $this->putJson('/api/v1/message', [
            'id' => $data->id,
            'subject' => "Alerta Covid V2",
            'body' => "Mi vecino tiene covid V2",
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => true,
            ]);

        $response = $this->getJson('/api/v1/message/type', [
            ]);

        $response
            ->assertStatus(200);

        $response = $this->getJson('/api/v1/message', [
            ]);

        $response
            ->assertStatus(200);


        Message::find($data->id)->delete();
    }
}
