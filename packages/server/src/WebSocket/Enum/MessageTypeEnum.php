<?php

declare(strict_types=1);

namespace App\WebSocket\Enum;

class MessageTypeEnum
{
    public const CONNECTION_CALLBACK = 'connection-callback';
    public const EVENT_NOTIFICATION = 'event-notification';
}
