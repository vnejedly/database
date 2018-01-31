<?php
namespace Hooloovoo\Database\Helper;

use Hooloovoo\Database\Exception\LogicException;

/**
 * Class TableLock
 */
class TableLock extends AbstractHelper
{
    const FIELD_ALIAS = 1;
    const FIELD_WRITE = 2;

    /** @var array[] */
    protected $_tables = [];

    /**
     * @param string $table
     * @param string $alias
     * @param bool $write
     * @return TableLock
     */
    public function addTable(string $table, bool $write, string $alias = null) : self
    {
        $this->_tables[$table] = [
            self::FIELD_ALIAS => $alias,
            self::FIELD_WRITE => $write
        ];

        return $this;
    }

    /**
     * @param TableLock $lock
     */
    public function mergeLock(self $lock)
    {
        foreach ($lock->getTables() as $table => $lockInfo) {
            $this->addTable($table, $lockInfo[self::FIELD_WRITE], $lockInfo[self::FIELD_ALIAS]);
        }
    }

    /**
     * @return TableLock
     */
    public function lock() : self
    {
        $this->_execute($this->_getLockQueryString());
        return $this;
    }

    /**
     * @return TableLock
     */
    public function unlock() : self
    {
        $this->_execute($this->_getUnlockQueryString());
        return $this;
    }

    /**
     * @return array
     */
    protected function getTables() : array
    {
        return $this->_tables;
    }

    /**
     * @return string
     */
    protected function _getLockQueryString()
    {
        if (0 == count($this->_tables)) {
            throw new LogicException("No table to lock");
        }

        $subStrings = [];
        foreach ($this->_tables as $table => $lock) {
            if (!is_null($lock[self::FIELD_ALIAS])) {
                $aliasPart = "AS {$lock[self::FIELD_ALIAS]}";
            } else {
                $aliasPart = "";
            }

            if ($lock[self::FIELD_WRITE]) {
                $writePart = 'WRITE';
            } else {
                $writePart = 'READ';
            }

            $subStrings[] = "$table $aliasPart $writePart";
        }

        $subStringsImploded = implode(', ', $subStrings);
        return "LOCK TABLES $subStringsImploded";
    }


    protected function _getUnlockQueryString()
    {
        return 'UNLOCK TABLES';
    }
}