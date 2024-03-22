<?php

declare(strict_types=1);

use Happyr\DoctrineSpecification\Repository\EntitySpecificationRepository;
use Shlinkio\Shlink\Core\Config\EnvVars;

use function Shlinkio\Shlink\Core\ArrayUtils\contains;

return (static function (): array {
    $driver = EnvVars::DB_DRIVER->loadFromEnv();
    $isMysqlCompatible = contains($driver, ['maria', 'mysql']);

    $resolveDriver = static fn () => match ($driver) {
        'postgres' => 'pdo_pgsql',
        'mssql' => 'pdo_sqlsrv',
        default => 'pdo_mysql',
    };
    $readCredentialAsString = static function (EnvVars $envVar): string|null {
        $value = $envVar->loadFromEnv();
        return $value === null ? null : (string) $value;
    };
    $resolveDefaultPort = static fn () => match ($driver) {
        'postgres' => '5432',
        'mssql' => '1433',
        default => '3306',
    };
    $resolveCharset = static fn () => match ($driver) {
        // This does not determine charsets or collations in tables or columns, but the charset used in the data
        // flowing in the connection, so it has to match what has been set in the database.
        'maria', 'mysql' => 'utf8mb4',
        'postgres' => 'utf8',
        default => null,
    };

    $resolveConnection = static fn () => match ($driver) {
        null, 'sqlite' => [
            'driver' => 'pdo_sqlite',
            'path' => 'data/database.sqlite',
        ],
        default => [
            'driver' => $resolveDriver(),
            //'dbname' => EnvVars::DB_NAME->loadFromEnv('shlink'),
            'url' => EnvVars::DATABASE_URL->loadFromEnv(),
            //'user' => $readCredentialAsString(EnvVars::DB_USER),
            //'password' => $readCredentialAsString(EnvVars::DB_PASSWORD),
            'host' => EnvVars::DB_HOST->loadFromEnv(EnvVars::DB_UNIX_SOCKET->loadFromEnv()),
            'port' => EnvVars::DB_PORT->loadFromEnv($resolveDefaultPort()),
            'unix_socket' => $isMysqlCompatible ? EnvVars::DB_UNIX_SOCKET->loadFromEnv() : null,
            'charset' => $resolveCharset(),
            'driverOptions' => $driver !== 'mssql' ? [] : [
                'TrustServerCertificate' => 'true',
            ],
        ],
    };

    return [

        'entity_manager' => [
            'orm' => [
                'proxies_dir' => 'data/proxies',
                'load_mappings_using_functional_style' => true,
                'default_repository_classname' => EntitySpecificationRepository::class,
            ],
            'connection' => $resolveConnection(),
        ],

    ];
})();
