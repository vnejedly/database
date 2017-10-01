<?php
namespace Hooloovoo\Database\Query;
use Hooloovoo\Database\Query\Param\Param;

/**
 * Interface QueryInterface
 */
interface QueryInterface
{
    /**
     * @param string $sql
     */
    public function prepend(string $sql) ;

    /**
     * @param string $sql
     */
    public function append(string $sql) ;

    /**
     * @param string $alias
     * @param QueryInterface $subQuery
     */
    public function addSubQuery(string $alias, QueryInterface $subQuery) ;

    /**
     * Resets all params
     */
    public function resetParams() ;

    /**
     * @param string $name
     * @param $value
     * @param int $type
     */
    public function addParam(string $name, $value, int $type) ;

    /**
     * @param string $name
     * @param mixed[] $values
     * @param int $type
     */
    public function addMultiParam(string $name, array $values, int $type) ;

    /**
     * @param Param[] $params
     */
    public function setParams(array $params) ;

    /**
     * @return Param[]
     */
    public function getParams() : array ;

    /**
     * @return string
     */
    public function getQueryString() : string ;
}