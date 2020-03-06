<?php

namespace BlackCup\Invites\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BlackCup\Invites\Invite
 */
class Invites extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'invites';
    }
}
