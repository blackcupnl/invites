<?php

namespace BlackCup\Invites\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['from_name', 'from_email', 'to_name', 'to_email', 'payload', 'message'];

    /**
     * @var OPEN Value for the open status
     */
    public const OPEN = 'open';

    /**
     * @var ACCEPTED Value for the open status
     */
    public const ACCEPTED = 'accepted';

    /**
     * @var REJECTED Value for the open status
     */
    public const REJECTED = 'rejected';

    /**
     * Sets the status attribute to ACCEPTED.
     *
     * @return self
     */
    public function accept()
    {
        $this->status = self::ACCEPTED;

        return $this;
    }

    /**
     * Sets the status attribute to REJECTED.
     *
     * @return self
     */
    public function reject()
    {
        $this->status = self::REJECTED;

        return $this;
    }

    /**
     * Getter for the payload attribute.
     *
     * @param mixed $value original payload value
     * @return mixed deserialized payload
     */
    public function getPayloadAttribute($value)
    {
        return unserialize($value);
    }

    /**
     * Setter for the payload attribute.
     *
     * @param mixed payload
     */
    public function setPayloadAttribute($value)
    {
        $this->attributes['payload'] = strval(serialize($value));
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
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

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKeyName()
    {
        return 'token';
    }
}
