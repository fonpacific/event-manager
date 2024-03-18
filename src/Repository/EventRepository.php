<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;

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

    public const EVENT_FILTER_ONLY_PAST= 0;
    public const EVENT_FILTER_ONLY_FUTURE= 1;
    public const EVENT_FILTER_ALL_TIME= 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findAvailable(): array {
        
        return $this->findAvailableQB()
                    ->getQuery()
                    ->getResult()
                    ;
    }

    public function upcomingEventsForUser(User $user): array {
        
        return $this->findAvailableQB([Event::STATUS_PUBLISHED, Event::STATUS_CANCELLED])
                    ->leftJoin('e.registrations', 'r', 'WITH', 'r.platformUser = :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getResult()
                    ;
    }

    public function historyEventsForUser(User $user): array {
        
        return $this->findAvailableQB([Event::STATUS_PUBLISHED, Event::STATUS_CANCELLED], self::EVENT_FILTER_ONLY_PAST)
                    ->leftJoin('e.registrations', 'r', 'WITH', 'r.platformUser = :user')
                    ->andWhere('r.platformUser= :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getResult()
                    ;
    }

    public function myEventsForOrganizer(User $user): array {
        
        return $this->findAvailableQB(Event::STATUSES, self::EVENT_FILTER_ONLY_PAST)
                    ->andWhere('e.organizer= :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getResult()
                    ;
    }

    public function search(string $query): array {
        $qb = $this->findAvailableQB(Event::STATUSES, self::EVENT_FILTER_ONLY_PAST);

        if ($query !== ''){
            $qb
                ->andWhere('e.name LIKE :query OR e.description LIKE :query')
                ->setParameter('query', "%$query%");
        }

        return $qb->getQuery()->getResult();
    }

    private function findAvailableQB(array $statuses = Event::STATUSES_AVAILABLE, int $mode= self::EVENT_FILTER_ONLY_FUTURE) : ORMQueryBuilder {
       
        $now= new \DateTime();
        $qb = $this->createQueryBuilder('e');

        if ($mode === self::EVENT_FILTER_ONLY_PAST ){
            $qb ->andWhere('e.startDate <= :now')
                ->setParameter('now', $now)
                ;
    }
        else if ($mode === self::EVENT_FILTER_ONLY_FUTURE ){
             $qb ->andWhere('e.endDate >= :now')
                ->setParameter('now', $now)
                ;
        }


        $qb 
            -> andWhere('e.status IN (:statuses)')
            ->setParameter('statuses', $statuses)
            ->orderBy('e.startDate', 'ASC')
        ;
        return $qb;
    }

    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //      
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
