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
    public function __construct(
        ManagerRegistry $registry,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($registry, Messages::class);
    }

    /**
     * Save a WebSocket message and set its runDate to the current date-time.
     */
    public function save(Messages $webSocketMessage): Messages
    {
        try {
            // Set the runDate to the current date-time
            if (is_null($webSocketMessage->getRunDate())) {
                $webSocketMessage->setRunDate(new \DateTimeImmutable());
            }

            // Persist and save the entity
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

    /**
     * Find all messages with a runDate before the current time.
     *
     * @return Messages[] Returns an array of Messages objects
     */
    public function findWithRunDateBeforeNow(): array
    {
        $currentDate = new \DateTimeImmutable('now');

        // Create QueryBuilder instance for the Messages entity
        $qb = $this->createQueryBuilder('m');

        return $qb->select('m')
            ->where('m.runDate < :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getResult();
    }
}
