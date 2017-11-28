<?php
namespace Which1ispink\API\DependencyInjection;

use Which1ispink\API\Core\DependencyInjection\ContainerInterface;
use Which1ispink\API\Mapper\CharacterMapper;
use Which1ispink\API\Service\CharacterService;
use PDO;

/**
 * Class ServicesProvider
 *
 * Responsible for registering services in the given container
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class ServicesProvider
{
    /**
     * Registers services in the given container
     *
     * @param ContainerInterface $container
     * @param array $appConfig
     * @return ContainerInterface
     */
    public function registerServices(ContainerInterface $container, array $appConfig): ContainerInterface
    {
        $container->set('db', function ($c) use ($appConfig) {
            return $this->createDatabaseConnection(
                $appConfig['database']['host'],
                $appConfig['database']['database'],
                $appConfig['database']['user'],
                $appConfig['database']['password'],
                $appConfig['database']['charset']
            );
        });

        $container->set('character_mapper', function ($c) {
            return new CharacterMapper($c->get('db'));
        });

        $container->set('character_service', function ($c) {
            return new CharacterService($c->get('character_mapper'));
        });

        return $container;
    }

    /**
     * Create and return a PDO database connection
     *
     * @param string $host
     * @param string $database
     * @param string $user
     * @param string $password
     * @param string $charset
     * @return PDO
     */
    private function createDatabaseConnection(string $host, string $database, string $user, string $password, string $charset): PDO
    {
        $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $user, $password, $opt);
    }
}
