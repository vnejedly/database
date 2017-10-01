<?php
namespace Hooloovoo\Database\QueryResult;

/**
 * Interface QueryResultInterface
 */
interface QueryResultInterface
{
    /**
     * @param bool $fetchAssoc
     * @return array
     */
    public function fetch(bool $fetchAssoc = true) : array;

    /**
     * @param bool $fetchAssoc
     * @return array
     */
    public function fetchOne(bool $fetchAssoc = true) : array;

    /**
     * @param bool $fetchAssoc
     * @return array
     */
    public function fetchAll(bool $fetchAssoc = true) : array;

    /**
     * @param int $columnNumber
     * @return array
     */
    public function fetchColumn(int $columnNumber) : array;

    /**
     * @return int
     */
    public function getAffectedRowsCount() : int;
}