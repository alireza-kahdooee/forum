<?php

namespace Tests\Feature\API\v1\Thread;

use App\Http\Controllers\API\V01\Channel\ThreadController;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{

    /**
     * @test
     */
    public function all_threads_list_should_be_accessible()
    {
        $response = $this->get(route('threads.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function thread_should_be_accessible_by_publish()
    {
        $thread = Thread::factory()->create();
        $response = $this->get(route('threads.show', $thread->slug));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function create_thread_should_be_validated()
    {
//        The user must login.
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('threads.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function create_new_thread()
    {
//        Used to indicate errors. And ignores handling error.
        $this->withoutExceptionHandling();

//        The user must login.
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('threads.store'), [
            'title' => 'foo',
            'content' => 'bar',
            'channel_id' => Channel::factory()->create()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function update_thread_should_be_validated()
    {
//        The user must login.
        Sanctum::actingAs(User::factory()->create());

        $thread = Thread::factory([
            'title' => 'foo',
            'content' => 'bar',
            'channel_id' => Channel::factory()->create()->id,
        ])->create();

//        $response = $this->json('PUT', route('threads.update', $thread), []);
        $response = $this->putJson(route('threads.update', $thread), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function update_thread()
    {
//        Used to indicate errors. And ignores handling error.
        $this->withoutExceptionHandling();

//        The user must login.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory([
            'title' => 'foo',
            'content' => 'bar',
            'user_id' => $user->id,
            'channel_id' => Channel::factory()->create()->id,
        ])->create();

//        $response = $this->json('PUT', route('threads.update', $thread), [        or
        $response = $this->putJson(route('threads.update', $thread), [
            'title' => 'bar',
            'content' => 'bar',
            'channel_id' => Channel::factory()->create()->id,
        ])->assertSuccessful();
//        $updatedThread = Thread::find($thread->id);   or
        $thread->refresh();
        $this->assertSame('bar', $thread->title);
    }

    /**
     * @test
     */
    public function update_best_answer_id_thread()
    {
//        Used to indicate errors. And ignores handling error.
        $this->withoutExceptionHandling();

//        The user must login.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create(['user_id' => $user->id]);

//        $response = $this->json('PUT', route('threads.update', $thread), [        or
        $response = $this->putJson(route('threads.update', $thread), [
            'best_answer_id' => 1,
        ])->assertSuccessful();
//        $updatedThread = Thread::find($thread->id);   or
        $thread->refresh();

        $this->assertSame('1', $thread->best_answer_id);
    }

    /**
     * @test
     */
    public function thread_destroy()
    {
//        The user must login.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create(['user_id' => $user->id]);

//        $response = $this->json('DELETE', route('threads.destroy'), ['id' => $thread->id]);       or
        $response = $this->delete(route('threads.destroy', $thread));

        $response->assertStatus(Response::HTTP_OK);
    }
}
