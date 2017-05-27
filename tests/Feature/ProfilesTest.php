<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group ProfilesTest
     * @test
     */
    public function a_user_has_a_profile()
    {
        $user = create(User::class);

        $this->get(route('profiles.show', $user->name))
            ->assertSee($user->name);
    }

    /**
     * @group ProfilesTest
     * @test
     */
    public function it_displays_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $thread = create(Thread::class, [ 'user_id' => auth()->id()]);

        $this->get(route('profiles.show', auth()->user()->name))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}