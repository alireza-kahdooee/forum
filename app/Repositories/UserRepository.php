<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @param Request $request
     * @return User
     */
    public function create(Request $request): User
    {
        $data = $request->except(['_token']);
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function find($ids)
    {
        return User::find($ids);
    }

    public function leaderBoards()
    {
        return User::query()->orderByDesc('score')->paginate(20);
    }

    public function isBlock()
    {
        return (bool)auth()->user()->is_block;
    }
}
