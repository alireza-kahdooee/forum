<?php


namespace App\Repositories;


use App\Models\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelRepositories
{
    public $name;

    /**
     * Get All Channels
     * @return Channel[]|Collection
     */
    public function index()
    {
        return Channel::all();
    }
    /**
     * @param Request $request
     */
    public function create($name): void
    {
        Channel::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }
    /**
     * @param Request $request
     */
    public function update($name, $id): void
    {
        Channel::find($id)->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    /**
     * @param Request $request
     */
    public function destroy($id)
    {
        Channel::destroy($id);
    }
}
