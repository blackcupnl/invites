<?php

namespace BlackCup\Invites;

use ReflectionClass;
use ReflectionProperty;
use BlackCup\Invites\Notifications\InvitedNotification;
use BlackCup\Invites\Notifications\InviteAcceptedNotification;
use BlackCup\Invites\Notifications\InviteRejectedNotification;

abstract class Invite
{
    protected $received_notification = InvitedNotification::class;
    protected $accepted_notification = InviteAcceptedNotification::class;
    protected $rejected_notification = InviteRejectedNotification::class;

    protected $accept_requires_authentication = false;
    protected $reject_requires_authentication = false;

    protected $description;

    public function send()
    {
    }

    public function accept()
    {
    }

    public function reject()
    {
    }

    public function requiresAuthentication($action)
    {
        return $this->{$action.'_requires_authentication'};
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __sleep()
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
