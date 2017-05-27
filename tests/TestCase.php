<?php

namespace Tests;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $oldExceptionHandler;

    public function setUp()
    {
        parent::setUp();

        $this->disableExceptionHandling();
    }
    protected function signIn($user = null)
    {
        $user = $user ?? create(\App\Models\User::class);
        $this->be($user);
        return $this;
    }

    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = app()->make(ExceptionHandler::class);
        app()->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(Exception $e){}
            public function render($request, Exception $e)
            {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        app()->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }
}
