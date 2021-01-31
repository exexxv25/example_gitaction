<?php

namespace Tests\Feature\Document;

use Tests\TestCase;
use App\Models\User;
use App\Models\Document;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
class DocumentTest extends TestCase
{
    /**
     * @group document
     * @test
     */
    public function documentsTest()
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

        $response = $this->postJson('/api/v1/document', [
            'user_id' => 1,
            'location_id' => 1,
            'name' => "Reglamento vecinos",
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'name' => true,
            ]);

        $data = json_decode($response->getContent());

        $version = mt_rand();

        $response = $this->putJson('/api/v1/document', [
            'id' => $data->id,
            'name' => "Reglamento vecinosV$version",
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'name' => true,
            ]);

        $response = $this->getJson('/api/v1/document', [
            ]);

        $response
            ->assertStatus(200);


        Document::find($data->id)->delete();
    }
}
