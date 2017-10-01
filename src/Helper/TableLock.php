<?php
namespace Hooloovoo\Database\Helper;

use Hooloovoo\Database\Exception\LogicException;

/**
 * Class TableLock
 */
class TableLock extends AbstractHelper
{
    const FIELD_TABLE = 1;
    const FIELD_ALIAS = 2;
    const FIELD_WRITE = 3;

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
        $this->_tables[] = [
            self::FIELD_TABLE => $table,
            self::FIELD_ALIAS => $alias,
            self::FIELD_WRITE => $write
        ];

        return $this;
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
     * @return string
     */
    protected function _getLockQueryString()
    {
        if (0 == count($this->_tables)) {
            throw new LogicException("No table to lock");
        }

        $subStrings = [];
        foreach ($this->_tables as $table) {
            if (!is_null($table[self::FIELD_ALIAS])) {
                $aliasPart = "AS {$table[self::FIELD_ALIAS]}";
            } else {
                $aliasPart = "";
            }

            if ($table[self::FIELD_WRITE]) {
                $writePart = 'WRITE';
            } else {
                $writePart = 'READ';
            }

            $subStrings[] = "{$table[self::FIELD_TABLE]} $aliasPart $writePart";
        }

        $subStringsImploded = implode(', ', $subStrings);
        return "LOCK TABLES $subStringsImploded";
    }


    protected function _getUnlockQueryString()
    {
        return 'UNLOCK TABLES';
    }
}