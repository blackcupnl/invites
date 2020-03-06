<?php

namespace DummyNamespace;

use BlackCup\Invites\Invite;

class DummyInvite extends Invite
{
    /**
     * Execute Invite specific code on send.
     *
     * @return void
     */
    public function send()
    {
        // This method is called when the invite is sent.
    }

    /**
     * Execute Invite specific code on accept.
     *
     * @return void
     */
    public function accept()
    {
        // This method is called when the invite is accepted.
    }

    /**
     * Execute Invite specific code on reject.
     *
     * @return void
     */
    public function reject()
    {
        // This method is called when the invite is rejected.
    }
}
