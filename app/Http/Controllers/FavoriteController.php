<?php
namespace App\Http\Controllers;

use App\Models\Reply;

class FavoriteController extends Controller
{
    /**
     * FavoriteController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new favorite in the database
     *
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        if(request()->expectsJson())
        {
            return response([], 204);
        }
        return back();
    }

    /**
     * Removes a favorite from the database
     *
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        if(request()->expectsJson())
        {
            return response([], 204);
        }

        return back();
    }
}
