<?php


namespace App\Repositories;


use App\Models\Thread;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreadRepositories
{
    /**
     * Get All Channels
     * @return Channel[]|Collection
     */
    public function index()
    {
        return Thread::wherePublish(1)->latest()->get();
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function show($slug)
    {
        return Thread::whereSlug($slug)->wherePublish(1)->first();
    }

    /**
     * @param Request $request
     */
    public function create(Request $request): void
    {
        auth()->user()->threads()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->input('content'),
            'channel_id' => $request->channel_id,
        ]);
    }

    /**
     * @param $request
     * @param Thread $thread
     */
    public function update($request, Thread $thread): void
    {
        if ($request->has('best_answer_id')) {
           $thread->update([
                'best_answer_id' => $request->best_answer_id,
            ]);
        } else {
            $thread->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->input('content'),
                'channel_id' => $request->channel_id,
            ]);
        }
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        Thread::destroy($id);
    }
}
