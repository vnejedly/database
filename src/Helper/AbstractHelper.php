<?php
namespace Hooloovoo\Database\Helper;

use Hooloovoo\Database\Connection\ConnectionInterface;
use Hooloovoo\Database\Query\Factory\QueryFactory;
use Hooloovoo\Database\QueryResult\QueryResultInterface;

/**
 * Class AbstractHelper
 */
abstract class AbstractHelper
{
    /** @var ConnectionInterface */
    protected $_connection;

    /** @var QueryFactory */
    protected $_queryFactory;

    /**
     * TableLock constructor.
     * @param ConnectionInterface $connection
     * @param QueryFactory $queryFactory
     */
    public function __construct(ConnectionInterface $connection, QueryFactory $queryFactory)
    {
        $this->_connection = $connection;
        $this->_queryFactory = $queryFactory;
    }

    /**
     * @param string $queryString
     * @return QueryResultInterface
     */
    protected function _execute(string $queryString) : QueryResultInterface
    {
        $query = $this->_queryFactory->getSpecific($queryString);
        return $this->_connection->execute($query);
    }
}