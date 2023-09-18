<?php

declare(strict_types=1);

namespace App\WebSocket\Manager;

use App\WebSocket\Entity\Messages;
use App\WebSocket\Enum\MessageTypeEnum;
use App\WebSocket\Repository\MessagesRepository;
use Psr\Log\LoggerInterface;

class MessageManager
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessagesRepository $messageRepository,
    ) {
    }

    public function sendMessage(Messages $message, ?\SplObjectStorage $clients): void
    {
        try {
            $data = $message->getData();
            $jsonData = json_encode($data);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \Exception('Malformed JSON Message');
            }

            foreach ($clients as $client) {
                if (false === isset($data['resourceId'])) {
                    $client->send($jsonData);
                    continue;
                }

                if ($client->resourceId === (int) $data['resourceId']) {
                    $client->send($jsonData);
                    break;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to send the message', ['exception' => $e]);
        } finally {
            $this->removeMessage($message);
        }
    }

    public function getQueuedMessages(): array
    {
        return $this->messageRepository->findWithRunDateBeforeNow();
    }

    public function createEventMessage(array $data = []): void
    {
        $webSocketMessage = new Messages();
        $webSocketMessage->setData($data);

        $this->messageRepository->save($webSocketMessage);
    }

    public function createNotificationMessage(string $message, string $resourceId = null, int $delayInSeconds = 0): void
    {
        $data = [
            'type' => MessageTypeEnum::EVENT_NOTIFICATION,
            'message' => $message,
            'resourceId' => $resourceId,
        ];

        $webSocketMessage = new Messages();
        $webSocketMessage->setData($data);

        // Add the delay to the current date-time
        $currentDateTime = new \DateTimeImmutable();
        $runDateWithDelay = $currentDateTime->add(new \DateInterval('PT'.$delayInSeconds.'S'));
        $webSocketMessage->setRunDate($runDateWithDelay);

        $this->messageRepository->save($webSocketMessage);
    }

    public function removeMessage(Messages $message): void
    {
        $this->messageRepository->delete($message);
    }
}
