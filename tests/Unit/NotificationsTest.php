<?php

namespace BlackCup\Invites\Tests\Unit;

use BlackCup\Invites\Models\Invite;
use BlackCup\Invites\Tests\TestCase;
use BlackCup\Invites\Facades\Invites;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use BlackCup\Invites\Notifications\InvitedNotification;
use BlackCup\Invites\Notifications\InviteAcceptedNotification;
use BlackCup\Invites\Notifications\InviteRejectedNotification;

class NotificationsTest extends TestCase
{
    private $data;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->data = factory(Invite::class)->make();

        $this->token = Invites::send($this->data->payload, $this->data->message, $this->data->to_name, $this->data->to_email, $this->data->from_name, $this->data->from_email);

        $this->invite = Invite::where('token', $this->token)->firstOrFail();
    }

    public function test_invited_notification_is_sent_to_recipient()
    {
        $this->assertNotificationSentTo($this->data->to_email, InvitedNotification::class);
    }

    public function test_accepted_notification_is_sent_to_sender()
    {
        Invites::accept($this->invite);

        $this->assertNotificationSentTo($this->data->from_email, InviteAcceptedNotification::class);
    }

    public function test_rejected_notification_is_sent_to_sender()
    {
        Invites::reject($this->invite);

        $this->assertNotificationSentTo($this->data->from_email, InviteRejectedNotification::class);
    }

    private function assertNotificationSentTo($email, $notification)
    {
        Notification::assertSentTo(new AnonymousNotifiable, $notification, function ($notification, $channels, $notifiable) use ($email) {
            return $channels == ['mail'] && $notifiable->routes['mail'] == $email;
        });
    }
}
