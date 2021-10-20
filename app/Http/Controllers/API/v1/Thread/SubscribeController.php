<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\SubscribeRepositories;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['user.block'])->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @param Thread $thread
     * @return JsonResponse
     */
    public function subscribe(Thread $thread)
    {
        resolve(SubscribeRepositories::class)->subscribe($thread->id);

        return response()->json([
            'message' => 'user subscribed successfully'
        ], Response::HTTP_OK);
    }

    /**
     * @param Thread $thread
     * @return JsonResponse
     */
    public function unSubscribe(Thread $thread)
    {
        resolve(SubscribeRepositories::class)->unSubscribe($thread->id);

        return response()->json([
            'message' => 'user unsubscribed successfully'
        ], Response::HTTP_OK);
    }
}
