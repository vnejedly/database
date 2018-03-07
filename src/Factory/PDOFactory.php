<?php
namespace Hooloovoo\Database\Factory;

use Hooloovoo\DI\Factory\AbstractFactory;
use PDO;

/**
 * Class PDOFactory
 * @method PDO getSingleton
 */
class PDOFactory extends AbstractFactory
{
    /** @var string */
    protected $_host;

    /** @var string */
    protected $_database;

    /** @var string */
    protected $_user;

    /** @var string */
    protected $_password;

    /** @var mixed[] */
    protected $_attributes = [];

    /**
     * PDOFactory constructor.
     * @param string $host
     * @param string $database
     * @param string $user
     * @param string $password
     */
    public function __construct(string $host, string $database, string $user, string $password)
    {
        $this->_host = $host;
        $this->_database = $database;
        $this->_user = $user;
        $this->_password = $password;
    }

    /**
     * @param int $name
     * @param mixed $value
     */
    public function setAttribute(int $name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * @return PDO
     */
    public function getNew()
    {
        $pdo = new PDO($this->_getDSN(), $this->_user, $this->_password);
        foreach ($this->_attributes as $name => $value) {
            $pdo->setAttribute($name, $value);
        }

        $pdo->exec('SET NAMES utf8');

        return $pdo;
    }

    /**
     * @return string
     */
    protected function _getDSN() : string
    {
        return "mysql:dbname={$this->_database};host={$this->_host}";
    }
}