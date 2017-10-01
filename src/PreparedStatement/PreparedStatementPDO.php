<?php
namespace Hooloovoo\Database\PreparedStatement;

use Hooloovoo\Database\Database;
use Hooloovoo\Database\Exception\ConnectionLostException;
use Hooloovoo\Database\Exception\ExecuteException;
use Hooloovoo\Database\Exception\PrepareException;
use Hooloovoo\Database\Query\Param\Param;
use Hooloovoo\Database\Query\QueryAwareTrait;
use Hooloovoo\Database\QueryResult\QueryResultInterface;
use Hooloovoo\Database\QueryResult\QueryResultPDO;
use PDOException;
use PDOStatement;
use PDO;

/**
 * Class PreparedStatementPDO
 */
class PreparedStatementPDO implements PreparedStatementInterface
{
    use QueryAwareTrait;

    /** @var PDOStatement */
    protected $_pdoStatement;

    /**
     * QueryAwareTrait constructor.
     * @param string $queryString
     * @param PDO $pdo
     */
    public function __construct(string $queryString, PDO $pdo)
    {
        $this->_queryString = $queryString;
        $this->_pdoStatement = $this->_prepare($pdo);
    }

    /**
     * @return QueryResultInterface
     */
    public function execute() : QueryResultInterface
    {
        try {
            foreach ($this->getParams() as $name => $param) {
                $this->_pdoStatement->bindValue($name, $param->getValue(), $this->_getParamType($param));
            }
            $this->_pdoStatement->execute();
        } catch (PDOException $exception) {
            $this->_checkLostConnection($exception);
            throw new ExecuteException($exception->getMessage());
        }

        return new QueryResultPDO($this->_pdoStatement);
    }

    /**
     * @param PDO $pdo
     * @return PDOStatement
     */
    protected function _prepare(PDO $pdo) : PDOStatement
    {
        try {
            return $pdo->prepare($this->_queryString);
        } catch (PDOException $exception) {
            $this->_checkLostConnection($exception);
            throw new PrepareException($exception->getMessage());
        }
    }

    /**
     * @param PDOException $exception
     */
    protected function _checkLostConnection(PDOException $exception)
    {
        if (
            count($exception->errorInfo) > 2
            && $exception->errorInfo[0] == 'HY000'
            && $exception->errorInfo[1] == 2006
        ) {
            throw new ConnectionLostException($exception->getMessage());
        }
    }

    /**
     * @param Param $param
     * @return int
     */
    protected function _getParamType(Param $param) : int
    {
        $mapping = [
            Database::PARAM_NULL => PDO::PARAM_NULL,
            Database::PARAM_BOOL => PDO::PARAM_BOOL,
            Database::PARAM_INT => PDO::PARAM_INT,
            Database::PARAM_STR => PDO::PARAM_STR,
            Database::PARAM_LOB => PDO::PARAM_LOB,
            Database::PARAM_STMT => PDO::PARAM_STMT
        ];

        return $mapping[$param->getType()];
    }
}