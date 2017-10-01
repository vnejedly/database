<?php
namespace Hooloovoo\Database\Query;

use Hooloovoo\Database\Query\Param\MultiParam;
use Hooloovoo\Database\Query\Param\Param;

/**
 * Class QueryAwareTrait
 */
trait QueryAwareTrait
{
    /** @var string */
    protected $_queryString;
    
    /** @var Param[] */
    protected $_params = [];

    /** @var MultiParam[] */
    protected $_multiParams = [];

    /**
     * Resets all params
     */
    public function resetParams()
    {
        $this->_params = [];
        $this->_multiParams = [];
    }

    /**
     * @param string $name
     * @param $value
     * @param int $type
     */
    public function addParam(string $name, $value, int $type)
    {
        $this->_params[$name] = new Param($type, $value);
    }

    /**
     * @param string $name
     * @param mixed[] $values
     * @param int $type
     */
    public function addMultiParam(string $name, array $values, int $type)
    {
        $this->_multiParams[$name] = new MultiParam($name, $type, $values);
    }

    /**
     * @param Param[] $params
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        $this->_multiParams = [];
    }

    /**
     * @return Param[]
     */
    public function getParams() : array
    {
        $transformedParams = [];
        foreach ($this->_multiParams as $multiParam) {
            $transformedParams = array_merge($transformedParams, $multiParam->getParams());
        }
        
        return array_merge($this->_params, $transformedParams);
    }

    /**
     * @return string
     */
    public function getQueryString() : string
    {
        $queryString = $this->_getOriginalQueryString();
        foreach ($this->_multiParams as $name => $multiParam) {
            $transformedNames = array_keys($multiParam->getParams());
            foreach ($transformedNames as &$transformedName) {
                $transformedName = ":$transformedName";
            }
            $replacement = implode(', ', $transformedNames);
            $queryString = str_replace(":$name", $replacement, $queryString);
        }

        return $queryString;
    }

    /**
     * @return string
     */
    protected function _getOriginalQueryString()
    {
        return $this->_queryString;
    }
}