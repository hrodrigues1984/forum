<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /**
     * @group ThreadTest
     * @test
     */
     public function it_can_make_a_string_path()
     {
        $thread = make(Thread::class);

        $this->assertEquals(
            url("/threads/{$thread->channel->slug}/{$thread->id}"), $thread->path()
        );
     }

    /**
     * @group ThreadTest
     * @test
     */
     public function it_has_a_creator()
     {
        $this->assertInstanceOf(User::class, $this->thread->creator);
     }

    /**
     * @group ThreadTest
     * @test
     */
    public function it_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /**
     * @group ThreadTest
     * @test
     */
    public function it_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'      => 'foobar',
            'user_id'   => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
    
    /**
     * @group ThreadTest
     * @test
     */
     public function it_belongs_to_a_channel()
     {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
     }

    /**
     * @group ThreadTest
     * @test
     */
    public function it_can_be_subscribed()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /**
     * @group ThreadTest
     * @test
     */
    public function it_can_be_unsubscribed()
    {
        $thread = create(Thread::class);

        $thread->unsubscribe($userId = 1);

        $this->assertCount(0, $thread->subscriptions);
    }

    /**
     * @group ThreadTest
     * @test
     */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }


}
