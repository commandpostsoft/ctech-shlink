<?php

declare(strict_types=1);

use Shlinkio\Shlink\Core\Config\EnvVars;

return (static function (): array {
    $redisServers = EnvVars::REDIS_SERVERS->loadFromEnv();
    $redis = ['pub_sub_enabled' => $redisServers !== null && EnvVars::REDIS_PUB_SUB_ENABLED->loadFromEnv(false)];
    $cacheRedisBlock = $redisServers === null ? [] : [
        'redis' => [
            'servers' => $redisServers,
            'sentinel_service' => EnvVars::REDIS_SENTINEL_SERVICE->loadFromEnv(),
            'decode_credentials' => (bool) EnvVars::REDIS_DECODE_CREDENTIALS->loadFromEnv(false),
        ],
    ];

    return [
        'cache' => [
            'namespace' => EnvVars::CACHE_NAMESPACE->loadFromEnv('Shlink'),
            ...$cacheRedisBlock,
        ],
        'redis' => $redis,
    ];
})();
