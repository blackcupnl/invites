<?php

namespace BlackCup\Invites\Tests\Unit;

use BlackCup\Invites\Models\Invite;
use BlackCup\Invites\Tests\TestCase;
use BlackCup\Invites\Facades\Invites;

class DatabaseTest extends TestCase
{
    private $data;
    private $token;
    private $invite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = factory(Invite::class)->make();

        $this->token = Invites::send($this->data->payload, $this->data->message, $this->data->to_name, $this->data->to_email, $this->data->from_name, $this->data->from_email);

        $this->invite = Invite::where('token', $this->token)->firstOrFail();
    }

    public function test_invited_notification_saved()
    {
        $this->assertDatabaseHas('invites', [
            'from_name' => $this->data->from_name,
            'from_email' => $this->data->from_email,
            'to_name' => $this->data->to_name,
            'to_email' => $this->data->to_email,
            'message' => $this->data->message,
            'token' => $this->token,
            'status' => Invite::OPEN,
        ]);
    }

    public function test_accepted_notification_saved()
    {
        Invites::accept($this->invite);

        $this->assertDatabaseHas('invites', [
            'from_name' => $this->data->from_name,
            'from_email' => $this->data->from_email,
            'to_name' => $this->data->to_name,
            'to_email' => $this->data->to_email,
            'message' => $this->data->message,
            'token' => $this->token,
            'status' => Invite::ACCEPTED,
        ]);
    }

    public function test_rejected_notification_saved()
    {
        Invites::reject($this->invite);

        $this->assertDatabaseHas('invites', [
            'from_name' => $this->data->from_name,
            'from_email' => $this->data->from_email,
            'to_name' => $this->data->to_name,
            'to_email' => $this->data->to_email,
            'message' => $this->data->message,
            'token' => $this->token,
            'status' => Invite::REJECTED,
        ]);
    }
}
