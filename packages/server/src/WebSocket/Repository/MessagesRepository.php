<?php

declare(strict_types=1);

namespace App\WebSocket\Repository;

use App\WebSocket\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->logger = $logger;
        parent::__construct($registry, Messages::class);
    }

    public function save(Messages $webSocketMessage): Messages
    {
        try {
            $this->_em->persist($webSocketMessage);
            $this->_em->flush();

            return $webSocketMessage;
        } catch (\Exception $e) {
            $this->logger->error('Failed to save the message', ['exception' => $e]);
            throw $e;
        }
    }

    public function delete(Messages $webSocketMessage): void
    {
        try {
            $this->_em->remove($webSocketMessage);
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->logger->error('Failed to delete the message', ['exception' => $e]);
            throw $e;
        }
    }
}
