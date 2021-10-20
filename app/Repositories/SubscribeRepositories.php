<?php


namespace App\Repositories;

use App\Models\Subscribe;

class SubscribeRepositories
{
    public function subscribe($id)
    {
        auth()->user()->subscribes()->create([
            'thread_id' => $id,
        ]);
    }

    public function unSubscribe($id)
    {
        auth()->user()->subscribes()->where([
            'thread_id' => $id,
        ])->delete();
    }

    public function getNotifiableUsers($threadID)
    {
        $userIDs = Subscribe::query()->where('thread_id', $threadID)->pluck('user_id')->all();

        return $userIDs;
    }
}
