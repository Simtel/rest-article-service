<?php

namespace App\Providers;

use App\Events\ExampleEvent;
use App\Listeners\ExampleListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<class-string>>
     */
    protected $listen = [
        ExampleEvent::class => [
            ExampleListener::class,
        ],
    ];
}
