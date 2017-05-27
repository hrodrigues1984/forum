<?php

namespace App\Filters;

use App\Models\User;

class ThreadFilters extends Filters
{

    protected $filters = [ 'by', 'popular', 'unanswered' ];
    /**
     * Filter the query by a given username
     *
     * @param string $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::whereName($username)->firstOrFail();
        return $this->builder->whereUserId($user->id);
    }

    /**
     * Filter the query according to most popular threads
     *
     * @return mixed
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Filter the query according to unanswered threads
     *
     * @return mixed
     */
    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}