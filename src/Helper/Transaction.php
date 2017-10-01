<?php
namespace Hooloovoo\Database\Helper;

/**
 * Class Transaction
 */
class Transaction extends AbstractHelper
{
    /** @var bool */
    protected $_active = false;

    /**
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->_active;
    }

    /**
     * Starts a transaction
     */
    public function start()
    {
        $this->_execute("START TRANSACTION");
        $this->_active = true;
    }

    /**
     * Commits the transaction
     */
    public function commit()
    {
        $this->_execute("COMMIT");
        $this->_active = false;
    }

    /**
     * Rolls the transaction back
     */
    public function rollback()
    {
        $this->_execute("ROLLBACK");
        $this->_active = false;
    }
}