<?php

declare(strict_types=1);

namespace Shlinkio\Shlink;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Shlinkio\Shlink\Common\Logger\LoggerFactory;
use Shlinkio\Shlink\Common\Logger\LoggerType;
use Shlinkio\Shlink\Common\Middleware\AccessLogMiddleware;
use Shlinkio\Shlink\Common\Middleware\RequestIdMiddleware;

use function Shlinkio\Shlink\Config\runningInRoadRunner;

return (static function (): array {
    $common = [
        'level' => Level::Info->value,
        'processors' => [RequestIdMiddleware::class],
        'line_format' =>
            '[%datetime%] [%extra.' . RequestIdMiddleware::ATTRIBUTE . '%] %channel%.%level_name% - %message%',
    ];

    return [

        'logger' => [
            'Shlink' => [
                'type' => LoggerType::FILE->value,
                ...$common,
            ],
            'Access' => [
                'type' => LoggerType::STREAM->value,
                'destination' => 'php://stderr',
                'add_new_line' => ! runningInRoadRunner(),
                ...$common,
            ],
        ],

        'dependencies' => [
            'factories' => [
                'Logger_Shlink' => [LoggerFactory::class, 'Shlink'],
                'Logger_Access' => [LoggerFactory::class, 'Access'],
                NullLogger::class => InvokableFactory::class,
            ],
            'aliases' => [
                'logger' => 'Logger_Shlink',
                Logger::class => 'Logger_Shlink',
                LoggerInterface::class => 'Logger_Shlink',
                AccessLogMiddleware::LOGGER_SERVICE_NAME => 'Logger_Access',
            ],
        ],

    ];
})();
