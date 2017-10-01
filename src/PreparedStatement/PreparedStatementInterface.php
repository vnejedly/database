<?php
namespace Hooloovoo\Database\PreparedStatement;

use Hooloovoo\Database\Query\Param\Param;
use Hooloovoo\Database\QueryResult\QueryResultInterface;

/**
 * Interface PreparedStatementInterface
 */
interface PreparedStatementInterface
{
    /**
     * Resets all params
     */
    public function resetParams();

    /**
     * @param string $name
     * @param mixed $value
     * @param int $type
     */
    public function addParam(string $name, $value, int $type);

    /**
     * @param Param[] $params
     */
    public function setParams(array $params);

    /**
     * @param string $name
     * @param mixed[][] $values
     * @param int $type
     */
    public function addMultiParam(string $name, array $values, int $type);

    /**
     * @return QueryResultInterface
     */
    public function execute() : QueryResultInterface;
}