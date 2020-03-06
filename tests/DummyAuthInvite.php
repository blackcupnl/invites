<?php

namespace BlackCup\Invites\Tests;

use BlackCup\Invites\Invite;

class DummyAuthInvite extends Invite
{
    protected $accept_requires_authentication = true;
    protected $reject_requires_authentication = true;

    public function send()
    {
        // This method is called when the invite is sent.
    }

    public function accept()
    {
        // This method is called when the invite is accepted.
    }

    public function reject()
    {
        // This method is called when the invite is rejected.
    }
}
