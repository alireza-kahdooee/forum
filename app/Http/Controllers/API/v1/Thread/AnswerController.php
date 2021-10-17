<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Repositories\AnswerRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AnswerController extends Controller
{
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

        // Update answer in Database
        resolve(AnswerRepositories::class)->update($request, $answer);

        return response()->json([
            'message' => 'answer updated successfully'
        ], Response::HTTP_OK);
    }

    public function destroy(Answer $answer)
    {
        // Delete answer in Database
        resolve(AnswerRepositories::class)->destroy($answer->id);

        return response()->json([
            'message' => 'answer destroy successfully'
        ], Response::HTTP_OK);
    }
}
