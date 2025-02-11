<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MessageCapsule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class MessageCapsuleTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_create_message_capsule()
    {

        $user = User::factory()->create();


        $this->actingAs($user, 'sanctum');


        $scheduledTime = Carbon::now()->addHour()->toDateTimeString();


        $response = $this->postJson('/api/message-capsules', [
            'message' => 'Test capsule message',
            'scheduledOpeningTime' => $scheduledTime,
        ]);

        $response->assertStatus(201);


        $capsule = MessageCapsule::first();


        $decryptedMessage = decrypt($capsule->message);

        $this->assertEquals('Test capsule message', $decryptedMessage);
    }


    public function test_user_can_retrieve_capsules()
    {
        $user = User::factory()->create();


        $capsule = MessageCapsule::factory()->create([
            'user_id' => $user->id,
            'opened' => false,
            'scheduledOpeningTime' => Carbon::now()->subMinute()->toDateTimeString(),
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/message-capsules');


        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $capsule->id,
                'message' => $capsule->message,
                'opened' => false,
            ]);
    }
}
