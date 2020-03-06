<?php

namespace BlackCup\Invites;

use BlackCup\Invites\Models\Invite;
use Illuminate\Support\Facades\Route;
use BlackCup\Invites\Invite as InviteImpl;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class Invites
{
    /**
     * Sends the given Invite implementation.
     *
     * @param InviteImpl $invite Invite implementation
     * @param string $message Text to descripbe the invite
     * @param string $to_name Invite recipient name
     * @param string $to_email Invite recipient e-mail address
     * @param string $from_name Invite sender name
     * @param string $from_email Invite sender e-mail address
     * @return string token for the sent Invite.
     **/
    public function send(InviteImpl $invite, string $message, string $to_name, string $to_email, $from_name, $from_email)
    {
        $model = Invite::create([
            'from_name' => $from_name,
            'from_email' => $from_email,
            'to_name' => $to_name,
            'to_email' => $to_email,
            'payload' => $invite,
            'message' => $message,
        ]);

        $invite->send();
        $this->notify($to_email, $model, $invite->received_notification);

        return $model->token;
    }

    /**
     * Accepts the given invite.
     *
     * @param Invite $model
     * @return void
     */
    public function accept(Invite $model)
    {
        $invite = $model->payload;

        $invite->accept();
        $this->notify($model->from_email, $model, $invite->accepted_notification);
        $model->accept()->save();
    }

    /**
     * rejects the given invite.
     *
     * @param Invite $model
     * @return void
     */
    public function reject(Invite $model)
    {
        $invite = $model->payload;

        $invite->reject();
        $this->notify($model->from_email, $model, $invite->rejected_notification);
        $model->reject()->save();
    }

    private function notify(string $to, Invite $model, string $notification)
    {
        if ($notification) {
            NotificationFacade::route('mail', $to)->notify(new $notification($model));
        }
    }

    /**
     * Publishes the routes for this package.
     *
     * @return void;
     */
    public static function routes()
    {
        Route::group([
            'namespace' => '\\BlackCup\\Invites\\Controllers',
            'middleware' => 'web',
            'where' => ['action' => '(accept|reject)'],
        ], function () {
            Route::get('invite/{invite}', 'InvitesController@show')->name('invites.show');
            Route::get('invite/{invite}/{action}', 'InvitesController@action')->name('invites.action');
            Route::post('invite/{invite}/{action}', 'InvitesController@execute')->name('invites.execute');
            Route::get('invite/{invite}/completed', 'InvitesController@completed')->name('invites.completed');
        });
    }
}
