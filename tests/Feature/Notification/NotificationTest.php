<?php

namespace Tests\Feature\Notification;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    /**
     * @group notification
     * @test
     */
    public function NotificationTest()
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

        $response = $this->postJson('/api/v1/notification', [
            'user_id' => 1,
            'location_id' => 1,
            'body' => "Se habilito un nuevo ginmasio",
            'subject' => "Nuevo gimnasio",
            'priority' => "normal"
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'subject' => true,
            ]);

        $data = json_decode($response->getContent());

        $response = $this->putJson('/api/v1/notification', [
            'id' => $data->id,
            'body' => "Se habilito una nueva seccion del gimnacio X",
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'body' => true,
            ]);

        $response = $this->getJson('/api/v1/notification', [
            ]);

        $response
            ->assertStatus(200);


        Notification::find($data->id)->delete();
    }
}

