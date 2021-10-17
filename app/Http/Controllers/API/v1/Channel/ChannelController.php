<?php

namespace App\Http\Controllers\API\v1\Channel;

use App\Http\Controllers\Controller;
use App\Repositories\ChannelRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends Controller
{
    public function index()
    {
        return response()->json(resolve(ChannelRepositories::class)->index(), Response::HTTP_OK);
    }

    /**
     * Create New Channel
     * @method GET
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required',
            ], [], []);

        // Insert channel to Database
        resolve(ChannelRepositories::class)->create($request->name);

        return response()->json([
            'message' => 'channel created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Update Channel
     * @method PUT
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $this->validate($request,
            [
                'id' => 'required',
                'name' => 'required',
            ], [], []);

        // Edit channel in Database
        resolve(ChannelRepositories::class)->update($request->name, $request->id);

        return response()->json([
            'message' => 'channel updated successfully'
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request)
    {
        $this->validate($request,
            [
                'id' => 'required',
            ], [], []);

        // Delete channel in Database
        resolve(ChannelRepositories::class)->destroy($request->id);

        return response()->json([
            'message' => 'channel destroy successfully'
        ], Response::HTTP_OK);
    }
}
