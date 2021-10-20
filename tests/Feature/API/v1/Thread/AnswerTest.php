<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function all_answers_list_should_be_accessible()
    {
        $response = $this->get(route('answers.index'));

//        $response->assertStatus(Response::HTTP_OK);       or
        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function create_answer_should_be_validated()
    {
//        The user must login.
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('answers.store'), []);

        $response->assertJsonValidationErrors(['content', 'thread_id']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function can_submit_new_answer_for_thread()
    {
//        Used to indicate errors. And ignores handling error.
        $this->withoutExceptionHandling();

//        The user must login.
        Sanctum::actingAs(User::factory()->create());

        $thread = Thread::factory()->create();

        $response = $this->postJson(route('answers.store'), [
            'content' => 'foo',
            'thread_id' => $thread->id,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'message' => 'answer submitted successfully'
        ]);

        $this->assertTrue($thread->answers()->where('content', 'foo')->exists());
    }

    /**
     * @test
     */
    public function user_score_will_be_increase_by_submit_new_answer()
    {
//        Used to indicate errors. And ignores handling error.
        $this->withoutExceptionHandling();

//        The user must login.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create();

        $response = $this->postJson(route('answers.store'), [
            'content' => 'foo',
            'thread_id' => $thread->id,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals(10, $user->score);
    }

    /**
     * @test
     */
    public function user_score_thread_owner_will_be_not_increase_by_submit_new_answer()
    {
//        Used to indicate errors. And ignores handling error.
        $this->withoutExceptionHandling();

//        The user must login.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory(['user_id' => $user->id])->create();

        $response = $this->postJson(route('answers.store'), [
            'content' => 'foo',
            'thread_id' => $thread->id,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);

        $user->refresh();
        $this->assertEquals(0, $user->score);
    }

    /**
     * @test
     */
    public function update_answer_should_be_validated()
    {
//        The user must login.
        Sanctum::actingAs(User::factory()->create());

        $answer = Answer::factory()->create();

//        $response = $this->json('PUT', route('answers.update', $answer), []);
        $response = $this->putJson(route('answers.update', $answer), []);
        $response->assertJsonValidationErrors(['content']);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function update_answer_of_thread()
    {
//        Used to indicate errors. And ignores handling error.
        $this->withoutExceptionHandling();

//        The user must login.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $answer = Answer::factory([
            'content' => 'foo',
            'user_id' => $user->id,
        ])->create();

//        $response = $this->json('PUT', route('answers.update', $answer), [        or
        $response = $this->putJson(route('answers.update', $answer), [
            'content' => 'bar',
        ])->assertSuccessful();

        $response->assertJson(['message' => 'answer updated successfully']);

//        $updatedThread = Thread::find($thread->id);   or
        $answer->refresh();
        $this->assertSame('bar', $answer->content);
    }

    /**
     * @test
     */
    public function answer_destroy()
    {
//        The user must login.
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $answer = Answer::factory()->create(['user_id' => $user->id]);

//        $response = $this->json('DELETE', route('answers.destroy'), ['id' => $answer->id]);       or
        $response = $this->delete(route('answers.destroy', $answer));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists());

        $response->assertJson(['message' => 'answer destroy successfully']);
    }
}
