<?php

namespace RexlManu\LaravelTickets;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RexlManu\LaravelTickets\Skeleton\SkeletonClass
 */
class LaravelTicketsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-tickets';
    }
}
