<?php


namespace App\Repositories;


use App\Models\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ChannelRepositories
{
    /**
     * Get All Channels
     * @return Channel[]|Collection
     */
    public function index()
    {
        return Channel::all();
    }

    /**
     * @param $name
     */
    public function create($name): void
    {
        Channel::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    /**
     * @param $name
     * @param $id
     */
    public function update($name, $id): void
    {
        Channel::find($id)->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        Channel::destroy($id);
    }
}
