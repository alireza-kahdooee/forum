<?php

namespace Tests\Feature\API\v1\Thread;

use App\Http\Controllers\API\v1\Thread\SubscribeController;
use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReplySubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_subscribe_to_a_channel()
    {
        Sanctum::actingAs(User::factory()->create());

        $thread = Thread::factory()->create();

        $response = $this->post(route('threads.subscribe', $thread));

        $response->assertSuccessful();

        $response->assertJson([
            'message' => 'user subscribed successfully'
        ]);
    }

    /**
     * @test
     */
    public function user_can_unsubscribe_to_a_channel()
    {
        Sanctum::actingAs(User::factory()->create());

        $thread = Thread::factory()->create();

        $response = $this->post(route('threads.unsubscribe', $thread));

        $response->assertSuccessful();

        $response->assertJson([
            'message' => 'user unsubscribed successfully'
        ]);
    }

    /**
     * @test
     */
    public function notification_will_sent_to_subscribes_of_a_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Notification::fake();

        $thread = Thread::factory()->create();

        $subscribeResponse = $this->post(route('threads.subscribe', $thread));
        $subscribeResponse->assertSuccessful();
        $subscribeResponse->assertJson([
            'message' => 'user subscribed successfully'
        ]);

        $asnwerResponse = $this->postJson(route('answers.store'), [
            'content' => 'foo',
            'thread_id' => $thread->id
        ]);
        $asnwerResponse->assertSuccessful();
        $asnwerResponse->assertJson([
            'message' => 'answer submitted successfully'
        ]);

        Notification::assertSentTo($user, NewReplySubmitted::class);
    }


}
