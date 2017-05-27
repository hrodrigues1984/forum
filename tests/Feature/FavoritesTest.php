<?php

namespace Tests\Feature;

use App\Models\Reply;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @group FavoritesTest
     * @test
     */
    public function guests_cant_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post(route('favorites.store', 1))
            ->assertRedirect(route('login'));
    }

    /**
     * @group FavoritesTest
     * @test
     */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->postJson(route('favorites.store', $reply->id))
            ->assertStatus(204);

        $this->assertCount(1, $reply->favorites);
    }

    /**
     * @group FavoritesTest
     * @test
     */
    public function an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $reply->favorite();

        $this->deleteJson(route('favorites.destroy', $reply->id))
            ->assertStatus(204);

        $this->assertCount(0, $reply->favorites);
    }

    /**
     * @group FavoritesTest
     * @test
     */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create(Reply::class);

        try{
            $this->post(route('favorites.store', $reply->id));
            $this->post(route('favorites.store', $reply->id));
        }catch (Exception $exception){
            $this->fail('Did not expect to insert the same record set twice');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
