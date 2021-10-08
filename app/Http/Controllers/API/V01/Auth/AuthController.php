<?php

namespace App\Http\Controllers\API\V01\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register new user
     * @method Post
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
        $data = $request->except(['_token']);
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return response()->json([
            'message' => 'user created successfully'
        ], 201);
    }

    public function login(Request $request)
    {

    }

    public function logout()
    {

    }
}
