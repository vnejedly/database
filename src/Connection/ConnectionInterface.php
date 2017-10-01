<?php
namespace Hooloovoo\Database\Connection;

use Hooloovoo\Database\PreparedStatement\PreparedStatementInterface;
use Hooloovoo\Database\Query\QueryInterface;
use Hooloovoo\Database\QueryResult\QueryResultInterface;

/**
 * Interface ConnectionInterface
 */
interface ConnectionInterface
{
    /**
     * @param QueryInterface $query
     * @return QueryResultInterface
     */
    public function execute(QueryInterface $query) : QueryResultInterface;

    /**
     * @param string $queryString
     * @return PreparedStatementInterface
     */
    public function getPreparedStatement(string $queryString) : PreparedStatementInterface;

    /**
     * @param string $name
     * @return int
     */
    public function getLastInsertedId(string $name = null) : int;

    /**
     * Tries to reconnect lost connection
     */
    public function reconnect();
}