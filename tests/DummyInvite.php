<?php

namespace BlackCup\Invites\Tests;

use BlackCup\Invites\Invite;

class DummyInvite extends Invite
{
    public function send()
    {
        event('DummyInvite.send');
    }

    public function accept()
    {
        event('DummyInvite.accept');
    }

    public function reject()
    {
        event('DummyInvite.reject');
    }
}
