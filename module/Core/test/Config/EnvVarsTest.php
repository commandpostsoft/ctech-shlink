<?php

declare(strict_types=1);

namespace ShlinkioTest\Shlink\Core\Config;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Shlinkio\Shlink\Core\Config\EnvVars;

use function putenv;

class EnvVarsTest extends TestCase
{
    protected function setUp(): void
    {
        putenv(EnvVars::BASE_PATH->value . '=the_base_path');
        putenv(EnvVars::DB_NAME->value . '=shlink');

        $envFilePath = __DIR__ . '/../DB_PASSWORD.env';
        putenv(EnvVars::DB_PASSWORD->value . '_FILE=' . $envFilePath);
    }

    protected function tearDown(): void
    {
        putenv(EnvVars::BASE_PATH->value . '=');
        putenv(EnvVars::DB_NAME->value . '=');
        putenv(EnvVars::DB_PASSWORD->value . '_FILE=');
    }

    #[Test, DataProvider('provideExistingEnvVars')]
    public function existsInEnvReturnsExpectedValue(EnvVars $envVar, bool $exists): void
    {
        self::assertEquals($exists, $envVar->existsInEnv());
    }

    public static function provideExistingEnvVars(): iterable
    {
        yield 'DB_NAME' => [EnvVars::DB_NAME, true];
        yield 'BASE_PATH' => [EnvVars::BASE_PATH, true];
        yield 'DB_DRIVER' => [EnvVars::DB_DRIVER, false];
        yield 'DEFAULT_REGULAR_404_REDIRECT' => [EnvVars::DEFAULT_REGULAR_404_REDIRECT, false];
    }

    #[Test, DataProvider('provideEnvVarsValues')]
    public function expectedValueIsLoadedFromEnv(EnvVars $envVar, mixed $expected, mixed $default): void
    {
        self::assertEquals($expected, $envVar->loadFromEnv($default));
    }

    public static function provideEnvVarsValues(): iterable
    {
        yield 'DB_NAME without default' => [EnvVars::DB_NAME, 'shlink', null];
        yield 'DB_NAME with default' => [EnvVars::DB_NAME, 'shlink', 'foobar'];
        yield 'BASE_PATH without default' => [EnvVars::BASE_PATH, 'the_base_path', null];
        yield 'BASE_PATH with default' => [EnvVars::BASE_PATH, 'the_base_path', 'foobar'];
        yield 'DB_DRIVER without default' => [EnvVars::DB_DRIVER, null, null];
        yield 'DB_DRIVER with default' => [EnvVars::DB_DRIVER, 'foobar', 'foobar'];
    }

    #[Test]
    public function fallsBackToReadEnvVarsFromFile(): void
    {
        self::assertEquals('this_is_the_password', EnvVars::DB_PASSWORD->loadFromEnv());
    }
}
