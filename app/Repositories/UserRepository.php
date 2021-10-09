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
    public function create(Request $request): void
    {
        $data = $request->except(['_token']);
        $data['password'] = Hash::make($data['password']);

        User::create($data);
    }
}
