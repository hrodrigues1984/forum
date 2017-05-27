<?php

namespace App\Providers;

use App\Models\Reply;
use App\Models\Thread;
use App\Policies\ReplyPolicy;
use App\Policies\ThreadPolicy;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Thread::class => ThreadPolicy::class,
        Reply::class => ReplyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function($user){
            if($user->email == 'lisandro60@example.org') return true;
        });
    }
}
