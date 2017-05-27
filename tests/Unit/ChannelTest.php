<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group ChannelTest
     * @test
     */
    public function it_consists_of_threads()
    {
        $channel = create(\App\Models\Channel::class);
        $thread = create(\App\Models\Thread::class, [ 'channel_id' => $channel->id ]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
