<?php

declare(strict_types=1);

namespace ShlinkioApiTest\Shlink\Rest\Action;

use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Shlinkio\Shlink\TestUtils\ApiTest\ApiTestCase;

class DomainRedirectsTest extends ApiTestCase
{
    #[Test, DataProvider('provideInvalidDomains')]
    public function anErrorIsReturnedWhenTryingToEditAnInvalidDomain(array $request): void
    {
        $resp = $this->callApiWithKey(self::METHOD_PATCH, '/domains/redirects', [
            RequestOptions::JSON => $request,
        ]);
        $payload = $this->getJsonResponsePayload($resp);

        self::assertEquals(self::STATUS_BAD_REQUEST, $resp->getStatusCode());
        self::assertEquals(self::STATUS_BAD_REQUEST, $payload['status']);
        self::assertEquals('https://shlink.io/api/error/invalid-data', $payload['type']);
        self::assertEquals('Provided data is not valid', $payload['detail']);
        self::assertEquals('Invalid data', $payload['title']);
    }

    public static function provideInvalidDomains(): iterable
    {
        yield 'no domain' => [[]];
        yield 'empty domain' => [['domain' => '']];
        yield 'null domain' => [['domain' => null]];
        yield 'invalid domain' => [['domain' => '192.168.1.1']];
    }

    #[Test, DataProvider('provideRequests')]
    public function allowsToEditDomainRedirects(array $request, array $expectedResponse): void
    {
        $resp = $this->callApiWithKey(self::METHOD_PATCH, '/domains/redirects', [
            RequestOptions::JSON => $request,
        ]);
        $payload = $this->getJsonResponsePayload($resp);

        self::assertEquals(self::STATUS_OK, $resp->getStatusCode());
        self::assertEquals($expectedResponse, $payload);
    }

    public static function provideRequests(): iterable
    {
        yield 'new domain' => [[
            'domain' => 'my-new-domain.com',
            'regular404Redirect' => 'foo.com',
        ], [
            'baseUrlRedirect' => null,
            'regular404Redirect' => 'foo.com',
            'invalidShortUrlRedirect' => null,
        ]];
        yield 'default domain' => [[
            'domain' => 's.test',
            'regular404Redirect' => 'foo-for-default.com',
        ], [
            'baseUrlRedirect' => null,
            'regular404Redirect' => 'foo-for-default.com',
            'invalidShortUrlRedirect' => null,
        ]];
        yield 'existing domain with redirects' => [[
            'domain' => 'detached-with-redirects.com',
            'baseUrlRedirect' => null,
            'invalidShortUrlRedirect' => 'foo.com',
        ], [
            'baseUrlRedirect' => null,
            'regular404Redirect' => 'bar.com',
            'invalidShortUrlRedirect' => 'foo.com',
        ]];
        yield 'existing domain with no redirects' => [[
            'domain' => 'example.com',
            'baseUrlRedirect' => null,
            'invalidShortUrlRedirect' => 'foo.com',
        ], [
            'baseUrlRedirect' => null,
            'regular404Redirect' => null,
            'invalidShortUrlRedirect' => 'foo.com',
        ]];
    }
}
