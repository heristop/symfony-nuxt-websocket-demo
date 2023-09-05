<?php

declare(strict_types=1);

namespace App\WebSocket\Entity;

use App\WebSocket\Repository\MessagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
#[ORM\Table(name: 'websocket_messages', indexes: [new ORM\Index(name: 'websocket_messages_idx', columns: ['id'])])]
class Messages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'json')]
    private array $data = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
