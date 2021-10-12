<?php

namespace Test\Unit\API\v1\Channel;

use App\Http\Controllers\API\V01\Channel\ChannelController;
use App\Models\Channel;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    /**
     * Test all Channel list should be accessible.
     * @test
     */
    public function test_all_channels_list_should_be_accessible()
    {
        $response = $this->get(route('channels.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test create channel
     * @test
     */
    public function test_create_channel_should_be_validated()
    {
        $channel = Channel::factory()->create();
        $response = $this->postJson(route('channels.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function test_create_new_channel()
    {
        $channel = Channel::factory()->create();
        $response = $this->postJson(route('channels.store'), [
            'name' => 'laravel'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test Update Channel
     * @test
     */
    public function test_channel_update_should_be_validated()
    {
        $response = $this->json('PUT', route('channels.update'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function test_channel_update()
    {
        $channel = Channel::factory([
            'name' => 'laravel'
        ])->create();
        $response = $this->json('PUT', route('channels.update'), [
            'id' => $channel->id,
            'name' => 'react.js'
        ]);
        $updatedChannel = Channel::find($channel->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('react.js', $updatedChannel->name);
    }

    /**
     * Test Destroy channel
     * @test
     */
    public function test_channel_destroy_should_be_validated()
    {
        $response = $this->json('DELETE', route('channels.destroy'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function test_channel_destroy()
    {
        $channel = Channel::factory()->create();
        $response = $this->json('DELETE', route('channels.destroy'), ['id' => $channel->id]);


        $response->assertStatus(Response::HTTP_OK);
    }
}
