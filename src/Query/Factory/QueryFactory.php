<?php
namespace Hooloovoo\Database\Query\Factory;

use Hooloovoo\Database\Query\Query;

/**
 * Class QueryFactory
 */
class QueryFactory
{
    /**
     * @param string $queryString
     * @return Query
     */
    public function getSpecific(string $queryString)
    {
        return new Query($queryString);
    }
}