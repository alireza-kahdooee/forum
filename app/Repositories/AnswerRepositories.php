<?php


namespace App\Repositories;


use App\Models\Answer;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnswerRepositories
{
    /**
     * Get All Channels
     * @return Channel[]|Collection
     */
    public function index()
    {
        return Answer::query()->latest()->get();
    }

    /**
     * @param Request $request
     */
    public function create(Request $request): void
    {
        $thread = Thread::find($request->thread_id);

        $thread->answers()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * @param $request
     * @param Answer $answer
     */
    public function update($request, Answer $answer): void
    {
        $answer->update([
            'content' => $request->input('content'),
        ]);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        Answer::destroy($id);
    }
}
