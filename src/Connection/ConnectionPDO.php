<?php
namespace Hooloovoo\Database\Connection;

use Hooloovoo\Database\Exception\ConnectionLostException;
use Hooloovoo\Database\Exception\ReconnectionFailedException;
use Hooloovoo\Database\Factory\PDOFactory;
use Hooloovoo\Database\PreparedStatement\PreparedStatementInterface;
use Hooloovoo\Database\PreparedStatement\PreparedStatementPDO;
use Hooloovoo\Database\Query\QueryInterface;
use Hooloovoo\Database\QueryResult\QueryResultInterface;
use Hooloovoo\Database\ReplicationExtension\ReplicationExtensionInterface;
use PDOException;

/**
 * Class ConnectionPDO
 */
class ConnectionPDO implements ConnectionInterface
{
    const REPLICATION_MASTER = true;
    const REPLICATION_SLAVE = false;

    /** @var PDOFactory */
    protected $_pdoFactory;

    /** @var bool  */
    protected $_isMaster;

    /** @var ReplicationExtensionInterface */
    protected $_replicationExtension;

    /**
     * ConnectionPDO constructor.
     * @param PDOFactory $pdoFactory
     * @param ReplicationExtensionInterface $replicationExtension
     * @param bool $isMaster
     */
    public function __construct(
        PDOFactory $pdoFactory,
        ReplicationExtensionInterface $replicationExtension,
        bool $isMaster = self::REPLICATION_MASTER
    ) {
        $this->_pdoFactory = $pdoFactory;
        $this->_replicationExtension = $replicationExtension;
        $this->_isMaster = $isMaster;
    }

    /**
     * @param QueryInterface $query
     * @return QueryResultInterface
     */
    public function execute(QueryInterface $query) : QueryResultInterface
    {
        $queryString = "/* {$this->_getReplicationSwitch()} */ {$query->getQueryString()}";
        $queryParams = $query->getParams();

        return $this->_performReConnective(function () use ($queryString, $queryParams) {
            $preparedPDOStatement = $this->getPreparedStatement($queryString);
            $preparedPDOStatement->setParams($queryParams);
            return $preparedPDOStatement->execute();
        });
    }

    /**
     * @param string $queryString
     * @return PreparedStatementInterface
     */
    public function getPreparedStatement(string $queryString) : PreparedStatementInterface
    {
        $queryString = "/* {$this->_getReplicationSwitch()} */ {$queryString}";

        return $this->_performReConnective(function () use ($queryString) {
            return new PreparedStatementPDO($queryString, $this->_pdoFactory->getSingleton());
        });
    }

    /**
     * @param string $name
     * @return int
     */
    public function getLastInsertedId(string $name = null) : int
    {
        return (int) $this->_pdoFactory->getSingleton()->lastInsertId($name);
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    protected function _performReConnective(callable $callback)
    {
        try {
            return $callback();
        } catch (ConnectionLostException $exception) {
            $this->reconnect();
            return $callback();
        }
    }

    /**
     * Tries to reconnect lost connection
     */
    public function reconnect()
    {
        try {
            $this->_pdoFactory->refreshSingleton();
        } catch (PDOException $exception) {
            throw new ReconnectionFailedException($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    protected function _getReplicationSwitch() : string
    {
        if ($this->_isMaster) {
            return $this->_replicationExtension->getMasterSwitch();
        } else {
            return $this->_replicationExtension->getSlaveSwitch();
        }
    }
}