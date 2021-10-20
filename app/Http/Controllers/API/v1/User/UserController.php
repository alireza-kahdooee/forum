<?php

namespace App\Http\Controllers\API\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function userNotification()
    {
        return \response()->json(\auth()->user()->unreadNotifications(), Response::HTTP_OK);
    }

    public function leaderBoards()
    {
        return resolve(UserRepository::class)->leaderBoards();
    }
}
