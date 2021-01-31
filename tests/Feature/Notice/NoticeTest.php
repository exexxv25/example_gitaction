<?php

namespace Tests\Feature\Notice;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notice;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoticeTest extends TestCase
{
    /**
     * @group notice
     * @test
     */
    public function NoticeTest()
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

        $response = $this->postJson('/api/v1/notice', [
            'user_id' => 1,
            'location_id' => 1,
            'body' => "Se habilito un nuevo ginmasio",
            'tittle' => "Nuevo gimnasio",
            'expired' => Carbon::today()->toDateString()
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'tittle' => true,
            ]);

        $data = json_decode($response->getContent());

        $version = mt_rand();

        $response = $this->putJson('/api/v1/notice', [
            'id' => $data->id,
            'body' => "Se habilito una nueva seccion del gimnacio X",
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'body' => true,
            ]);

        $response = $this->getJson('/api/v1/notice', [
            ]);

        $response
            ->assertStatus(200);


        Notice::find($data->id)->delete();
    }
}
