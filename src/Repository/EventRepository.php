<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{

    public const EVENT_FILTER_ONLY_PAST = 0;
    public const EVENT_FILTER_ONLY_FUTURE = 1;
    public const EVENT_FILTER_ALL_TIME = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAvailable(): array
    {
        return $this->findAvailableQB()
            ->getQuery()
            ->getResult()
        ;
    }

    public function upcomingEventsForUser(User $user): array
    {
        return $this->findAvailableQB([Event::STATUS_PUBLISHED, Event::STATUS_CANCELLED])
            ->leftJoin('e.registrations', 'r')
            ->andWhere('r.platformUser = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function historyEventsForUser(User $user): array
    {
        return $this->findAvailableQB([Event::STATUS_PUBLISHED, Event::STATUS_CANCELLED], mode: self::EVENT_FILTER_ONLY_PAST)
            ->leftJoin('e.registrations', 'r')
            ->andWhere('r.platformUser = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function myEventsForOrganizer(User $user): array
    {
        return $this->findAvailableQB(Event::STATUSES, mode: self::EVENT_FILTER_ALL_TIME)
            ->andWhere('e.organizer = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function search(string $query): array
    {
        $qb = $this->findAvailableQB(Event::STATUSES, mode: self::EVENT_FILTER_ALL_TIME);

        if ($query !== '') {
            $qb
                ->andWhere('e.name LIKE :query OR e.description LIKE :query')
                ->setParameter('query', "%$query%")
            ;
        }

        return $qb->getQuery()->getResult();
    }

    private function findAvailableQB(array $statuses = Event::STATUSES_AVAILABLE, int $mode = self::EVENT_FILTER_ONLY_FUTURE): QueryBuilder
    {
        $now = new \DateTime();
        $qb = $this->createQueryBuilder('e');

        if ($mode === self::EVENT_FILTER_ONLY_PAST) {
            $qb->andWhere('e.startDate <= :now')
                ->setParameter('now', $now);
        } elseif ($mode === self::EVENT_FILTER_ONLY_FUTURE) {
            $qb->andWhere('e.endDate >= :now')
                ->setParameter('now', $now);
        }

        $qb
            ->andWhere('e.status IN (:statuses)')
            ->setParameter('statuses', $statuses)
            ->orderBy('e.startDate', 'ASC');
        return $qb;
    }
}
