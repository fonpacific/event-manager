<?php

namespace App\Message;

class UserRegisteredToAnEvent
{
    public function __construct(public readonly int $userId, public readonly int $eventId)
    {

    }
}
