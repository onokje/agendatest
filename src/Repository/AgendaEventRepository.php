<?php

namespace App\Repository;

use App\Entity\AgendaEvent;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AgendaEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgendaEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgendaEvent[]    findAll()
 * @method AgendaEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgendaEvent::class);
    }

    public function getItemsByDateAndUser(\DateTimeInterface $dateTime, User $user): array
    {
        return $this->createQueryBuilder('e')
            ->Where('e.startsAt LIKE :date')
            ->andWhere('e.user = :user')
            ->setParameter(':date', $dateTime->format('Y-m-d') . "%")
            ->setParameter(':user', $user)
            ->orderBy('e.startsAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(AgendaEvent $agendaEvent)
    {
        $this->getEntityManager()->persist($agendaEvent);
        $this->getEntityManager()->flush();
    }

    // /**
    //  * @return AgendaEvent[] Returns an array of AgendaEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AgendaEvent
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
