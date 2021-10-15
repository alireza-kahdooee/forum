<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @param Request $request
     */
    public function create(Request $request): User
    {
        $data = $request->except(['_token']);
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }
}
