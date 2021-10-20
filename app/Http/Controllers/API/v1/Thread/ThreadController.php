<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\ThreadRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{

    public function __construct()
    {
        $this->middleware(['user.block'])->except(['index', 'show']);
    }

    public function index()
    {
        return response()->json(resolve(ThreadRepositories::class)->index(), Response::HTTP_OK);
    }

    public function show($slug)
    {
        return response()->json(resolve(ThreadRepositories::class)->show($slug), Response::HTTP_OK);
    }

    /**
     * Create New Channel
     * @method GET
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request,
            [
                'title' => 'required',
                'content' => 'required',
                'channel_id' => 'required',
            ], [], []);

        // Insert thread to Database
        resolve(ThreadRepositories::class)->create($request);

        return response()->json([
            'message' => 'thread created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Update Thread
     * @method PUT
     * @param Request $request
     * @param Thread $thread
     * @return JsonResponse
     */
    public function update(Request $request, Thread $thread)
    {
        if ($request->has('best_answer_id')) {
            $this->validate($request,
                [
                    'best_answer_id' => 'required',
                ], [], []);
        } else {
            $this->validate($request,
                [
                    'title' => 'required',
                    'content' => 'required',
                    'channel_id' => 'required',
                ], [], []);
        }

        if (Gate::forUser(auth()->user())->allows('update-thread', $thread)) {
            // Update thread in Database
            resolve(ThreadRepositories::class)->update($request, $thread);

            return response()->json([
                'message' => 'thread updated successfully'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'thread update access denied'
        ], Response::HTTP_FORBIDDEN);
    }

    public function destroy(Thread $thread)
    {
        if (Gate::forUser(auth()->user())->allows('delete-thread', $thread)) {
            // Delete thread in Database
            resolve(ThreadRepositories::class)->destroy($thread->id);

            return response()->json([
                'message' => 'thread destroy successfully'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'thread destroy access denied'
        ], Response::HTTP_FORBIDDEN);
    }
}
