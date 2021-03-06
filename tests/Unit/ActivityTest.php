<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group ActivityTest
     * @test
     */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create(Thread::class, [ 'user_id' => auth()->id()]); //same as 'App\Thread'

        $this->assertDatabaseHas('activities', [
            'type'          => 'created_thread',
            'user_id'       => auth()->id(),
            'subject_id'    => $thread->id,
            'subject_type'  => Thread::class //same as 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
        $this->assertEquals($activity->user_id, $thread->user_id);
    }

    /**
     * @group ActivityTest
     * @test
     */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->assertEquals(2, Activity::count());
    }

    /**
     * @group ActivityTest
     * @test
     */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        create(Thread::class, [ 'user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update([
            'created_at' => Carbon::now()->subWeek()
        ]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));

    }



}
