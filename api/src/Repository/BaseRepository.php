<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

abstract class BaseRepository extends ServiceEntityRepository
{
    protected ObjectRepository $objectRepository;
    protected Connection $connection;

    public function __construct(
        ManagerRegistry $registry,
        Connection $connection
    ){
        parent::__construct($registry, $this->entityClass());
        $this->connection = $connection;
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
        $this->_em->persist($entity);
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function flushData()
    {
        $this->_em->flush();
        $this->_em->clear();
    }


    /**
     * @param object $entity
     * @return object
     */
    public function saveEntity(object $entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    /**
     * @param object $entity
     * @return object
     */
    public function removeEntity(object $entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();

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