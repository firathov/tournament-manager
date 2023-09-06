<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\{EntityManager, OptimisticLockException, UnitOfWork};
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

/**
 * Class AbstractRepository.
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    protected EntityManager $entityManager;

    /**
     * AbstractRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param string          $entityClass
     *
     * @throws LogicException
     */
    public function __construct(
        ManagerRegistry $registry,
        string $entityClass
    ) {
        parent::__construct($registry, $entityClass);
        $this->entityManager = $this->getEntityManager();
    }

    /**
     * @param $object
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return mixed
     */
    public function insert($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush($object);

        return $object;
    }

    /**
     * @param $object
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove($object): void
    {
        $this->entityManager->remove($object);
        $this->entityManager->flush($object);
    }
}
