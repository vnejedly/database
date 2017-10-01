<?php
namespace Hooloovoo\Database\Query\Param;

/**
 * Class Param
 */
class Param
{
    /** @var string */
    protected $_type;

    /** @var mixed */
    protected $_value;

    /**
     * Param constructor.
     * @param string $type
     * @param mixed $value
     */
    public function __construct(string $type, $value)
    {
        $this->_type = $type;
        $this->_value = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }
}