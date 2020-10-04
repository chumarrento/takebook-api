<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\BookAccepted' => [
			'App\Listeners\BookAcceptedListener',
        ],
		'App\Events\NewNotification' => [
			'App\Listeners\NewNotificationListener',
		],
		'App\Events\NewChatMessage' => [
			'App\Listeners\MessageChatListener',
		],
		'App\Events\NewChatRoom' => [],
    ];
}
