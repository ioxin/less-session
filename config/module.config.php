<?php

use Less\Session\Services\SessionService;
use Less\Session\Services\SessionServiceFactory;
use Less\Session\Containers\Session;
use Less\Session\Containers\SessionFactory;

return [
    'service_manager' => [
        'factories' => [
            Session::class => SessionFactory::class,
            SessionService::class => SessionServiceFactory::class
        ]
    ]
];