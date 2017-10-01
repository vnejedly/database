<?php
namespace Hooloovoo\Database;

use Hooloovoo\Database\Connection\ConnectionInterface;
use Hooloovoo\Database\Helper\TableLock;
use Hooloovoo\Database\Helper\Transaction;
use Hooloovoo\Database\Query\Factory\QueryFactory;
use Hooloovoo\Database\Query\Query;

/**
 * Class Database
 */
class Database
{
    const PARAM_NULL = 0;
    const PARAM_BOOL = 1;
    const PARAM_INT = 2;
    const PARAM_STR = 3;
    const PARAM_LOB = 4;
    const PARAM_STMT = 5;

    /** @var ConnectionInterface */
    protected $_connectionMaster;

    /** @var ConnectionInterface */
    protected $_connectionSlave;

    /** @var QueryFactory */
    protected $_queryFactory;

    /** @var Transaction */
    protected $_transaction;

    /**
     * Database constructor.
     * @param ConnectionInterface $connectionMaster
     * @param ConnectionInterface $connectionSlave
     * @param QueryFactory $queryFactory
     */
    public function __construct(
        ConnectionInterface $connectionMaster,
        ConnectionInterface $connectionSlave,
        QueryFactory $queryFactory
    ) {
        $this->_connectionMaster = $connectionMaster;
        $this->_connectionSlave = $connectionSlave;
        $this->_queryFactory = $queryFactory;
    }

    /**
     * @param string $queryString
     * @return Query
     */
    public function createQuery(string $queryString) : Query
    {
        return $this->_queryFactory->getSpecific($queryString);
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnectionMaster(): ConnectionInterface
    {
        return $this->_connectionMaster;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnectionSlave(): ConnectionInterface
    {
        return $this->_connectionSlave;
    }

    /**
     * @return Transaction
     */
    public function transaction()
    {
        if (is_null($this->_transaction)) {
            $this->_transaction = new Transaction($this->getConnectionMaster(), $this->_queryFactory);
        }

        return $this->_transaction;
    }

    /**
     * @return TableLock
     */
    public function createLock()
    {
        return new TableLock($this->getConnectionMaster(), $this->_queryFactory);
    }
}