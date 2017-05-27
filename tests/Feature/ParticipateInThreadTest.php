<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group ParticipateInThreadTest
     * @test
     */
    public function an_unauthenticated_user_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('threads/some-channel/1/replies')
            ->assertRedirect(route('login'));
    }

    /**
     * @group ParticipateInThreadTest
     * @test
     */
     public function an_authenticated_user_may_participate_in_forum_threads()
     {
         $this->signIn();

         $thread = create(Thread::class);

         $reply = make(Reply::class);

         $this->post($thread->path() . '/replies', $reply->toArray());

         $this->assertDatabaseHas('replies', [ 'body' => $reply->body ]);
         $this->assertEquals(1, $thread->fresh()->replies_count);
     }

    /**
     * @group ParticipateInThreadTest
     * @test
     */
    public function it_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /**
     * @group ParticipateInThreadTest
     * @test
     */
    public function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->delete(route('replies.destroy', $reply->id))
            ->assertRedirect('login');

        $this->signIn()
            ->delete(route('replies.destroy', $reply->id))
            ->assertStatus(403);
    }

    /**
     * @group ParticipateInThreadTest
     * @test
     */
    public function authorized_users_cant_delete_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, [ 'user_id' => auth()->id()]);

        $this->delete(route('replies.destroy', $reply->id));

        $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /**
     * @group ParticipateInThreadTest
     * @test
     */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, [ 'user_id' => auth()->id()]);

        $updatedReply = 'You been changed, fool.';

        $this->patch(route('replies.update', $reply->id), [ 'body' => $updatedReply]);

        $this->assertDatabaseHas('replies', [ 'id' => $reply->id, 'body' => $updatedReply]);
    }

    /**
     * @group ParticipateInThreadTest
     * @test
     */
    public function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->patch(route('replies.update', $reply->id))
            ->assertRedirect('login');

        $this->signIn()
            ->patch(route('replies.update', $reply->id))
            ->assertStatus(403);
    }
}
