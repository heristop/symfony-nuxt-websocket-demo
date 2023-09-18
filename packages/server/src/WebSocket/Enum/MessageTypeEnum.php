<?php

declare(strict_types=1);

namespace App\WebSocket\Enum;

/**
 * Defines message types for WebSocket communication.
 */
enum MessageTypeEnum: string
{
    /**
     * Indicates an event notification message.
     */
    case EVENT_NOTIFICATION = 'event-notification';

    /**
     * Indicates a connection callback message.
     */
    case CONNECTION_CALLBACK = 'connection-callback';
}
