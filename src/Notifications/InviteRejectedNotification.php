<?php

namespace BlackCup\Invites\Notifications;

use Illuminate\Bus\Queueable;
use BlackCup\Invites\Models\Invite;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InviteRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $model;

    /**
     * Create a new notification instance.
     *
     * @param Invite $model
     * @return void
     */
    public function __construct(Invite $model)
    {
        $this->model = $model;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->error()
            ->subject(trans('invites::lang.invite_rejected'))
            ->greeting(trans('invites::lang.greeting', ['name' => $this->model->from_name]))
            ->line(trans('invites::lang.invite_rejected_long', ['name' => $this->model->to_name]));
    }
}
