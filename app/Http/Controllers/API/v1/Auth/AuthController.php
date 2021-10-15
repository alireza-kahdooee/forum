<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Register new user
     * @method POST
     * @param Request $request
     */
    public function register(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ], [], []);

        //Insert User into database
        $user = resolve(UserRepository::class)->create($request);

        $defaultSuperAdminEmail = config('permission.default_super_admin_email');
        ($user->email == $defaultSuperAdminEmail) ? $user->assignRole('Super Admin') : $user->assignRole('User');

        return response()->json([
            'message' => 'user created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Login for user
     * @method GET
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required|email',
                'password' => 'required',
            ], [], []);
        $data = $request->except(['_token']);

        if (auth()->attempt([$data['email'], $data['password']])) {
            return response()->json(Auth::user(), Response::HTTP_OK);
        }

        throw ValidationException::withMessages([
            'email' => 'incorrect credentials',
        ]);
    }

    public function user()
    {
        return response()->json(Auth::user(), Response::HTTP_OK);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'logged out successfully'
        ], Response::HTTP_OK);
    }
}
