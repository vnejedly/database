<?php
namespace Hooloovoo\Database\Query\Param;

/**
 * Class MultiParam
 */
class MultiParam
{
    /** @var string */
    protected $_name;

    /** @var string */
    protected $_type;

    /** @var mixed[] */
    protected $_values = [];

    /** @var Param[] */
    protected $_params = [];

    /**
     * Param constructor.
     * @param string $name
     * @param string $type
     * @param mixed[] $values
     */
    public function __construct(string $name, string $type, array $values)
    {
        $this->_name = $name;
        $this->_type = $type;
        $this->_values = $values;
        $this->_transform();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return mixed[]
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * @return Param[]
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Transforms values to new params
     */
    protected function _transform()
    {
        $index = 1;
        foreach ($this->_values as $value) {
            $paramName = "{$this->_name}_{$index}";
            $this->_params[$paramName] = new Param($this->_type, $value);
            $index++;
        }
    }
}