<?php

namespace Tests\Feature\Message;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageHistory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageHistoryTest extends TestCase
{
    /**
     * @group messagehistory
     * @test
     */
    public function MessageHistoryTest()
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
            'subject' => "otra notifi",
            'body' => "Mi vecino tiene un perro molesto",
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'subject' => true,
            ]);

        $message = json_decode($response->getContent());

        $response = $this->postJson('/api/v1/history/message/response', [
            'fk_user_id' => 1,
            'fk_message_id' => $message->id,
            'body' => "Mi vecino tiene un perro molesto",
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'id' => true,
            ]);


        $history = json_decode($response->getContent());

        $response = $this->putJson('/api/v1/history/message/edit', [
            'id' => $history->id,
            'body' => "Mi vecino tiene un gato molesto",
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => true,
            ]);

        $response = $this->getJson("/api/v1/history/message/all/$message->id", [
            ]);

        $response
            ->assertStatus(200);


        Message::find($message->id)->delete();
        MessageHistory::find($history->id)->delete();
    }
}
