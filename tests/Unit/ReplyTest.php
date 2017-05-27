<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group ReplyTest
     * @test
     */
     public function it_has_an_owner()
     {
        $reply = create(\App\Models\Reply::class);

        $this->assertInstanceOf(\App\Models\User::class, $reply->owner);
     }
}
