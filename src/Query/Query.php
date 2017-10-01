<?php
namespace Hooloovoo\Database\Query;

/**
 * Class Query
 */
class Query implements QueryInterface
{
    use QueryAwareTrait;

    /** @var QueryInterface[] */
    protected $subQueries = [];

    /**
     * QueryAwareTrait constructor.
     * @param string $queryString
     */
    public function __construct(string $queryString)
    {
        $this->_queryString = $queryString;
    }

    /**
     * @param string $alias
     * @param QueryInterface $subQuery
     */
    public function addSubQuery(string $alias, QueryInterface $subQuery)
    {
        $this->subQueries[$alias] = $subQuery;
        $this->_params = array_merge($this->_params, $subQuery->getParams());
    }

    /**
     * @param string $sql
     */
    public function prepend(string $sql)
    {
        $this->_queryString = "$sql {$this->_queryString}";
    }

    /**
     * @param string $sql
     */
    public function append(string $sql)
    {
        $this->_queryString = "{$this->_queryString} $sql";
    }

    /**
     * @return string
     */
    protected function _getOriginalQueryString()
    {
        $placeholders = [];
        $replacements = [];
        foreach ($this->subQueries as $alias => $subQuery) {
            $placeholders[] = '{&' . $alias . '}';
            $replacements[] = $subQuery->getQueryString();
        }

        return str_replace($placeholders, $replacements, $this->_queryString);
    }
}