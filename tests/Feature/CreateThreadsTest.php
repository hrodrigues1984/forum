<?php

namespace Tests\Feature;

use App\Models\Reply;
use Tests\TestCase;
use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group CreateThreadsTest
     * @test
     */
     public function it_prevents_guests_from_creating_threads()
     {
         $this->withExceptionHandling();

         $this->get('/threads/create')
             ->assertRedirect(route('login'));

         $this->post('/threads')
            ->assertRedirect(route('login'));
     }

    /**
     * @group CreateThreadsTest
     * @test
     */
     public function an_authenticated_user_can_create_new_threads()
     {
         $this->signIn();

         $thread = make(Thread::class);

         $response = $this->post('/threads', $thread->toArray());

         $this->get($response->headers->get('Location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
     }
     
     /**
      * @group CreateThreadsTest
      * @test
      */
      public function it_requires_a_title()
      {
          $this->publishThread(['title' => null])
              ->assertSessionHasErrors('title');
      }

    /**
     * @group CreateThreadsTest
     * @test
     */
    public function it_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /**
     * @group CreateThreadsTest
     * @test
     */
    public function it_requires_a_valid_channel()
    {
        create(Channel::class, [], 2);

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /**
     * @group CreateThreadsTest
     * @test
     */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $this->delete($thread->path())
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);
    }


    /**
     * @group CreateThreadsTest
     * @test
     */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', [ 'id' => $thread->id ]);

        $this->assertDatabaseMissing('replies', [ 'id' => $reply->id ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $thread->id,
            'subject_type'  => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'    => $reply->id,
            'subject_type'  => get_class($reply)
        ]);
    }


    private function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

         $thread = make(Thread::class, $overrides);

         return $this->post(route('threads.store'), $thread->toArray());
    }
}
