<?php

namespace App\Repository;

use App\Dto\ClientSearchDto;
use App\Entity\Client;
use App\enum\StatusDette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    //    /**
    //     * @return Client[] Returns an array of Client objects
    //     */
    public function paginateClients(int $page, int $limit): Paginator
    {

        $query = $this->createQueryBuilder('c')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->orderBy('c.id', 'ASC')
            ->getQuery();
        return new Paginator($query);
    }


    //    /**
    //     * @return Dette[] Returns an array of Dette objects
    //     */
    public function findClientBy(ClientSearchDto $clientSearchDto, int $page, int $limit): Paginator
    {

        $query = $this->createQueryBuilder('c');
        if (!empty($clientSearchDto->telephone)) {
            $query->andWhere('c.telephone = :telephone')
                ->setParameter('telephone', $clientSearchDto->telephone);
        }
        if (!empty($clientSearchDto->surname)) {
            $query->andWhere('c.surname = :surname')
                ->setParameter('surname', $clientSearchDto->surname);
        }
        $query->orderBy('c.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();
        return new Paginator($query);
    }

    // public function findDetteByClient(int $idClient, StatusDette $status = StatusDette::Impaye,  int $limit = 2, int $page = 1): Paginator
    // {
    //     $query = $this->createQueryBuilder('c');
    //     $query->join('c.dettes', 'd');
    //     $query->where('c.id = :idClient');
    //     $query->setParameter('idClient', $idClient);
    //     if ($status == StatusDette::Impaye) {
    //         $query->andWhere('d.montant != d.montantVerser');
    //     }
    //     if ($status == StatusDette::Paye) {
    //         $query->andWhere('d.montant = d.montantVerser');
    //     }

    //     $query->orderBy('d.createAt', 'DESC')
    //         ->setFirstResult(($page - 1) * $limit)
    //         ->setMaxResults($limit)
    //         ->getQuery();
    //     return new Paginator($query);
    // }

    public function findDetteByClient(int $idClient, StatusDette $status = StatusDette::Paye)
    {
        $query = $this->createQueryBuilder('c');
        $query->join('c.dettes', 'd');
        $query->where('c.id = :idClient');
        $query->setParameter('idClient', $idClient);
        if ($status->value == StatusDette::Impaye->value) {
            // dd($status->value == StatusDette::Impaye->value);
            $query->andWhere('d.montant != d.montantVerser');
        }
        if ($status->value == StatusDette::Paye->value) {
            $query->andWhere('d.montant = d.montantVerser');
        }

        return $query->orderBy('d.createAt', 'DESC')
            ->getQuery()
            ->getSingleResult();
    }

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
