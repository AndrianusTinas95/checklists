<?php

use App\Traits\LoginTrait;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    use LoginTrait;
    
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function header()
    {
        return [
            'HTTP_Authorization'=> $this->token(),
        ];
    }
}
