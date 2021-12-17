<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class BaseRepository
 * @package App\Repository
 */
abstract class BaseRepository
{
    private ManagerRegistry $managerRegistry;
    protected Connection $connection;
    protected ObjectRepository $objectRepository;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
    {
        $this->managerRegistry = $managerRegistry;
        $this->connection = $connection;
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    abstract protected static function entityClass(): string;

    /**
     * @throws ORMException
     */
    public function persistEntity(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     */
    public function flushData(): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(object $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeEntity(object $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }


    /**
     * @param string $query
     * @param array $params
     * @return \mixed[][]
     * @throws \Doctrine\DBAL\Exception
     */
    protected function executeFetchQuery(string $query, array $params = [])
    {
        //$this->connection->executeQuery($query, $params)->fetchAll(); #fetchAll está deprecado
        return $this->connection->executeQuery($query, $params)->fetchAllAssociative();
    }

    /**
     * @param string $query
     * @param array $params
     * @return \Doctrine\DBAL\Result
     * @throws \Doctrine\DBAL\Exception
     */
    protected function executeQuery(string $query, array $params = [])
    {
        return $this->connection->executeQuery($query, $params);
    }

    /**
     * @return ObjectManager|EntityManager
     */
    private function getEntityManager()
    {
        $entityManager = $this->managerRegistry->getManager();

        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    }
}