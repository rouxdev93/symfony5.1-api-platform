<?php


namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class BaseRepository
 * @package App\Repository
 */
abstract class BaseRepository
{
    protected EntityManagerInterface $entityManager;
    protected ObjectRepository $objectRepository;

    /**
     * BaseRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->connection = $this->entityManager->getConnection();
        $this->objectRepository = $this->entityManager->getRepository($this->entityClass());
    }

    /**
     * @return string
     */
    abstract protected static function entityClass(): string;

    /**
     * @param object $entity
     */
    public function persistEntity(object $entity)
    {
        $this->entityManager->persist($entity);
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function flushData()
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }


    /**
     * @param object $entity
     * @return object
     */
    public function saveEntity(object $entity)
    {
        $this->persistEntity($entity);
        $this->flushData();

        return $entity;
    }

    /**
     * @param object $entity
     * @return object
     */
    public function removeEntity(object $entity)
    {
        $this->entityManager->remove($entity);
        $this->flushData();

        return $entity;
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