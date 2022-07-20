<?php

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    public function createApplication(): Application
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
