<?php

namespace Blog\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Zend\Db\ResultSet\HydratingResultSet;

//use Zend\Db\ResultSet\ResultSet;

class ZendDbSqlRepository implements PostRepositoryInterface
{
    /**
     * @var AdapterInterface
     */
    private $db;

    /**
     * @param AdapterInterface $db
     */
    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllPosts()
{
    $sql       = new Sql($this->db);
    $select    = $sql->select('posts');
    $statement = $sql->prepareStatementForSqlObject($select);
    $result    = $statement->execute();

    if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
        return [];
    }

    $resultSet = new HydratingResultSet(
        new ReflectionHydrator(),
        new Post('', '')
    );
    $resultSet->initialize($result);
    return $resultSet;
}

    /**
     * {@inheritDoc}
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function findPost($id)
    {
    }
}