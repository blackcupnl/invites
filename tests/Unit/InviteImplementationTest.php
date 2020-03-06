<?php

namespace BlackCup\Invites\Tests\Unit;

use BlackCup\Invites\Models\Invite;
use BlackCup\Invites\Tests\TestCase;
use BlackCup\Invites\Facades\Invites;
use Illuminate\Support\Facades\Event;

class InviteImplementationTest extends TestCase
{
    private $data;
    private $token;
    private $invite;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake(['DummyInvite.send', 'DummyInvite.accept', 'DummyInvite.reject']);

        $this->data = factory(Invite::class)->make();

        $this->token = Invites::send($this->data->payload, $this->data->message, $this->data->to_name, $this->data->to_email, $this->data->from_name, $this->data->from_email);

        $this->invite = Invite::where('token', $this->token)->firstOrFail();
    }

    public function test_invite_send_function_is_called_at_send()
    {
        Event::assertDispatched('DummyInvite.send', 1);
    }

    public function test_invite_accept_function_is_called_at_accept()
    {
        Invites::accept($this->invite);

        Event::assertDispatched('DummyInvite.accept', 1);
    }

    public function test_invite_reject_function_is_called_at_reject()
    {
        Invites::reject($this->invite);

        Event::assertDispatched('DummyInvite.reject', 1);
    }
}
