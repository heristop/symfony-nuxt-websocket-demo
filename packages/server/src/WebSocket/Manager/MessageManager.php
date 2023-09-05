<?php

declare(strict_types=1);

namespace App\WebSocket\Manager;

use App\WebSocket\Entity\Messages;
use App\WebSocket\Enum\MessageTypeEnum;
use App\WebSocket\Repository\MessagesRepository;
use Psr\Log\LoggerInterface;

class MessageManager
{
    private MessagesRepository $messageRepository;
    private LoggerInterface $logger;

    public function __construct(MessagesRepository $messageRepository, LoggerInterface $logger)
    {
        $this->messageRepository = $messageRepository;
        $this->logger = $logger;
    }

    public function sendMessage(Messages $message, ?\SplObjectStorage $clients): void
    {
        try {
            $data = $message->getData();

            // Either the message is addressed to all, or to a specific client
            foreach ($clients as $client) {
                if (false === isset($data['resourceId'])) {
                    $client->send(json_encode($data));

                    continue;
                }

                if ($client->resourceId === (int) $data['resourceId']) {
                    $client->send(json_encode($data));

                    break;
                }
            }

            $this->removeMessage($message);
        } catch (\Exception $e) {
            $this->logger->error('Failed to send the message', ['exception' => $e]);
        }
    }

    public function getQueuedMessages(): array
    {
        return $this->messageRepository->findAll();
    }

    public function createEventMessage(array $data = []): void
    {
        $webSocketMessage = new Messages();
        $webSocketMessage->setData($data);

        $this->messageRepository->save($webSocketMessage);
    }

    public function createNotificationMessage(string $message, string $resourceId = null): void
    {
        $webSocketMessage = new Messages();

        $data = [
            'type' => MessageTypeEnum::EVENT_NOTIFICATION,
            'message' => $message,
            'resourceId' => $resourceId,
        ];

        $webSocketMessage->setData($data);

        $this->messageRepository->save($webSocketMessage);
    }

    public function removeMessage(Messages $message): void
    {
        $this->messageRepository->delete($message);
    }
}
