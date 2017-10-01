<?php
namespace Hooloovoo\Database\QueryResult;

use Hooloovoo\Database\Exception\FetchException;
use Hooloovoo\Database\Exception\RowNotFoundException;
use PDOStatement;
use Throwable;
use PDO;

/**
 * Class QueryResultPDO
 */
class QueryResultPDO implements QueryResultInterface
{
    /** @var PDOStatement */
    protected $_pdoStatement;

    /**
     * QueryResultPDO constructor.
     * @param PDOStatement $pdoStatement
     */
    public function __construct(PDOStatement $pdoStatement)
    {
        $this->_pdoStatement = $pdoStatement;
    }

    /**
     * @param bool $fetchAssoc
     * @return array
     */
    public function fetch(bool $fetchAssoc = true) : array
    {
        try {
            $data = $this->_pdoStatement->fetch($this->_getFetchMode($fetchAssoc));
        } catch (Throwable $exception) {
            throw new FetchException($exception->getMessage());
        }

        return $data;
    }

    /**
     * @param bool $fetchAssoc
     * @return array
     */
    public function fetchOne(bool $fetchAssoc = true) : array
    {
        $result = $this->fetch($fetchAssoc);

        if ($result === false) {
            throw new RowNotFoundException();
        }

        return $result;
    }

    /**
     * @param bool $fetchAssoc
     * @return array
     */
    public function fetchAll(bool $fetchAssoc = true) : array
    {
        try {
            $data = $this->_pdoStatement->fetchAll($this->_getFetchMode($fetchAssoc));
        } catch (Throwable $exception) {
            throw new FetchException($exception->getMessage());
        }

        return $data;
    }

    /**
     * @param int $columnNumber
     * @return array
     */
    public function fetchColumn(int $columnNumber) : array
    {
        try {
            $data = $this->_pdoStatement->fetchColumn($columnNumber);
        } catch (Throwable $exception) {
            throw new FetchException($exception->getMessage());
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getAffectedRowsCount() : int
    {
        return $this->_pdoStatement->rowCount();
    }

    /**
     * @param bool $fetchAssoc
     * @return int
     */
    protected function _getFetchMode(bool $fetchAssoc) : int
    {
        return $fetchAssoc ? PDO::FETCH_ASSOC : PDO::FETCH_NUM;
    }
}