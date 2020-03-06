<?php

namespace BlackCup\Invites;

use App\User;
use Notification;
use Illuminate\Support\Facades\Route;
use BlackCup\Invites\Models\Invite as Model;

class Invites
{
    public function send(Invite $invite, string $message, string $to_name, string $to_email, $from, $from_email = null)
    {
        if ($from instanceof User) {
            $from_email = $from->email;
            $from = $from->name;
        }

        $model = Model::create([
            'from_name' => $from,
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

    public function accept(Model $model)
    {
        $invite = $model->payload;

        $invite->accept();
        $this->notify($model->from_email, $model, $invite->accepted_notification);
        $model->accept()->save();
    }

    public function reject(Model $model)
    {
        $invite = $model->payload;

        $invite->reject();
        $this->notify($model->from_email, $model, $invite->rejected_notification);
        $model->reject()->save();
    }

    private function notify($to, $model, $notification)
    {
        if ($notification) {
            Notification::route('mail', $to)->notify(new $notification($model));
        }
    }

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
