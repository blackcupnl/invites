<?php

namespace BlackCup\Invites\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    public $fillable = ['from_name', 'from_email', 'to_name', 'to_email', 'payload', 'message'];

    public const OPEN = 'open';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';

    public function accept()
    {
        $this->status = self::ACCEPTED;

        return $this;
    }

    public function reject()
    {
        $this->status = self::REJECTED;

        return $this;
    }

    public function getPayloadAttribute($value)
    {
        return unserialize($value);
    }

    public function setPayloadAttribute($value)
    {
        $this->attributes['payload'] = strval(serialize($value));
    }

    protected static function boot()
    {
        parent::boot();
        self::saving(function ($model) {
            if (empty($model->token)) {
                do {
                    $model->token = Str::random(32);
                } while (self::where('token', $model->token)->exists());
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'token';
    }
}
