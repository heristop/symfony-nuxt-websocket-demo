<?php

declare(strict_types=1);

namespace App\WebSocket\Server;

use App\WebSocket\Manager\MessageManager;
use Psr\Log\LoggerInterface;
use Ratchet\Server\IoServer;

class CustomIoServer extends IoServer
{
    public const WATCH_INTERVAL_SECONDS = 1;

    private MessageManager $webSocketManager;
    private LoggerInterface $logger;

    public function attach(MessageManager $webSocketManager, LoggerInterface $logger): self
    {
        $this->webSocketManager = $webSocketManager;
        $this->logger = $logger;

        return $this;
    }

    public function listenPeriodically(WebSocketServer $webSocketServer): self
    {
        $this->loop->addPeriodicTimer(self::WATCH_INTERVAL_SECONDS, function () use ($webSocketServer): void {
            $this->processMessages($webSocketServer);
        });

        return $this;
    }

    public function listenOnce(WebSocketServer $webSocketServer): self
    {
        $this->loop->addTimer(self::WATCH_INTERVAL_SECONDS, function () use ($webSocketServer): void {
            $this->processMessages($webSocketServer);
            $this->loop->stop();
        });

        return $this;
    }

    private function processMessages(WebSocketServer $webSocketServer): void
    {
        try {
            foreach ($this->webSocketManager->getQueuedMessages() as $message) {
                $this->webSocketManager->sendMessage($message, $webSocketServer->getClients());
            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to process messages', ['exception' => $e]);
        }
    }
}
