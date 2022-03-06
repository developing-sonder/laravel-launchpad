<?php

namespace DevelopingSonder\LaravelLaunchpad;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DevelopingSonder\LaravelLaunchpad\Skeleton\SkeletonClass
 */
class LaravelLaunchpadFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-launchpad';
    }
}
