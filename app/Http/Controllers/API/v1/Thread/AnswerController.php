<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Subscribe;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReplySubmitted;
use App\Repositories\AnswerRepositories;
use App\Repositories\SubscribeRepositories;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['user.block'])->except(['index']);
    }

    public function index()
    {
        return response()->json(resolve(AnswerRepositories::class)->index(), Response::HTTP_OK);
    }

    /**
     * Create New Answer
     * @method GET
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request,
            [
                'content' => 'required',
                'thread_id' => 'required',
            ], [], []);

        // Insert answer to Database
        resolve(AnswerRepositories::class)->create($request);

        $users = resolve(UserRepository::class)->find(resolve(SubscribeRepositories::class)->
        getNotifiableUsers($request->thread_id));
        $thread = Thread::find($request->thread_id);
        Notification::send($users, new NewReplySubmitted($thread));

        if (auth()->user()->id != $thread->user_id) {
            auth()->user()->increment('score', 10);
        }

        return response()->json([
            'message' => 'answer submitted successfully'
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Answer $answer)
    {
        $this->validate($request,
            [
                'content' => 'required',
            ], [], []);

        if (Gate::forUser(auth()->user())->allows('delete-answer', $answer)) {
            // Update answer in Database
            resolve(AnswerRepositories::class)->update($request, $answer);

            return response()->json([
                'message' => 'answer updated successfully'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'answer update access denied'
        ], Response::HTTP_FORBIDDEN);
    }

    public function destroy(Answer $answer)
    {
        if (Gate::forUser(auth()->user())->allows('delete-answer', $answer)) {
            // Delete answer in Database
            resolve(AnswerRepositories::class)->destroy($answer->id);

            return response()->json([
                'message' => 'answer destroy successfully'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'answer destroy access denied'
        ], Response::HTTP_FORBIDDEN);
    }
}
