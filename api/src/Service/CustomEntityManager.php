<?php
declare(strict_types=1);

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectRepository;

abstract class CustomEntityManager
{
    protected EntityManagerInterface $entityManager;
    protected Connection $connection;
    protected ObjectRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        Connection $connection
    ){
        $this->entityManager = $entityManager;
        $this->connection = $connection;
        $this->repository = $entityManager->getRepository($this->entityClass());
    }

    abstract protected static function entityClass(): string;

    /**
     * @throws ORMException
     */
    public function persistEntity(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     */
    public function flushData(): void
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(object $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeEntity(object $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
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
}