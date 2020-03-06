<?php

namespace BlackCup\Invites;

use ReflectionClass;
use ReflectionProperty;
use BlackCup\Invites\Notifications\InvitedNotification;
use BlackCup\Invites\Notifications\InviteAcceptedNotification;
use BlackCup\Invites\Notifications\InviteRejectedNotification;

abstract class Invite
{
    /**
     * The Notification to send to the recipient of the invite.
     *
     * @var string|null Notification class
     */
    protected $received_notification = InvitedNotification::class;

    /**
     * The Notification to send to the sender when the invite has been accepted.
     *
     * @var string|null Notification class
     */
    protected $accepted_notification = InviteAcceptedNotification::class;

    /**
     * The Notification to send to the sender when the invite has been rejected.
     *
     * @var string|null Notification class
     */
    protected $rejected_notification = InviteRejectedNotification::class;

    /**
     * Indicates if accepting the Invite can only be done by an authenticated user.
     *
     * @var bool
     */
    protected $accept_requires_authentication = false;

    /**
     * Indicates if rejecting the Invite can only be done by an authenticated user.
     *
     * @var bool
     */
    protected $reject_requires_authentication = false;

    /**
     * Description of the invite.
     *
     * @var string|null
     */
    protected $description;

    /**
     * Execute Invite specific code on send.
     *
     * @return void
     */
    public function send()
    {
    }

    /**
     * Execute Invite specific code on accept.
     *
     * @return void
     */
    public function accept()
    {
    }

    /**
     * Execute Invite specific code on reject.
     *
     * @return void
     */
    public function reject()
    {
    }

    /**
     * Indicates if the specified action required authentication.
     *
     * @param string $action (accept|reject)
     * @return bool
     */
    final public function requiresAuthentication($action)
    {
        return $this->{$action.'_requires_authentication'};
    }

    final public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    final public function __sleep()
    {
        $data = new ReflectionClass($this);
        $properties = $data->getProperties(ReflectionProperty::IS_PUBLIC);
        $public_properties = [];

        foreach ($properties as $prop) {
            if ($prop->class == $data->getName()) {
                $public_properties[] = $prop->name;
            }
        }

        return $public_properties;
    }
}
