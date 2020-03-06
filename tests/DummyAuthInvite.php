<?php

namespace BlackCup\Invites\Tests;

use BlackCup\Invites\Invite;

class DummyAuthInvite extends Invite
{
    protected $accept_requires_authentication = true;
    protected $reject_requires_authentication = true;
}
