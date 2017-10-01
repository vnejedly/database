<?php
namespace Hooloovoo\Database\DI;

use Hooloovoo\Database\Connection\ConnectionPDO;
use Hooloovoo\Database\Database;
use Hooloovoo\Database\Factory\PDOFactory;
use Hooloovoo\Database\Query\Factory\QueryFactory;
use Hooloovoo\Database\ReplicationExtension\None;
use Hooloovoo\DI\Container\ContainerInterface;
use Hooloovoo\DI\Definition\AbstractDefinitionClass;
use Hooloovoo\DI\ObjectHolder\Singleton;
use PDO;

/**
 * Class ContainerDefinition
 */
class ContainerDefinition extends AbstractDefinitionClass
{
    /** @var string */
    protected $host;

    /** @var string */
    protected $database;

    /** @var string */
    protected $userName;

    /** @var string */
    protected $password;

    /** @var bool */
    protected $emulatePrepares;

    /**
     * Container constructor.
     *
     * @param string $host
     * @param string $database
     * @param string $userName
     * @param string $password
     * @param bool $emulatePrepares
     */
    public function __construct(string $host, string $database, string $userName, string $password, bool $emulatePrepares = false)
    {
        $this->host = $host;
        $this->database = $database;
        $this->userName = $userName;
        $this->password = $password;
        $this->emulatePrepares = $emulatePrepares;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setUpContainer(ContainerInterface $container)
    {
        $container->add(Database::class, new Singleton(function () use ($container) {
            return new Database(
                new ConnectionPDO($container->get(PDOFactory::class), $container->get(None::class), true),
                new ConnectionPDO($container->get(PDOFactory::class), $container->get(None::class), false),
                $container->get(QueryFactory::class)
            );
        }));

        $container->addFactory(PDOFactory::class, PDO::class, function () {
            $pdoFactory = new PDOFactory($this->host, $this->database, $this->userName, $this->password);
            $pdoFactory->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdoFactory->setAttribute(PDO::ATTR_EMULATE_PREPARES, $this->emulatePrepares);

            return $pdoFactory;
        });
    }
}