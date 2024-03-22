<?php

declare(strict_types=1);

namespace ShlinkioApiTest\Shlink\Core\Action;

use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use Shlinkio\Shlink\TestUtils\ApiTest\ApiTestCase;

use const ShlinkioTest\Shlink\ANDROID_USER_AGENT;
use const ShlinkioTest\Shlink\DESKTOP_USER_AGENT;
use const ShlinkioTest\Shlink\IOS_USER_AGENT;

class RedirectTest extends ApiTestCase
{
    #[Test, DataProvider('provideRequestOptions')]
    public function properRedirectHappensBasedOnRedirectRules(array $options, string $expectedRedirect): void
    {
        $response = $this->callShortUrl('def456', $options);

        self::assertEquals(302, $response->getStatusCode());
        self::assertEquals($expectedRedirect, $response->getHeaderLine('Location'));
    }

    public static function provideRequestOptions(): iterable
    {
        yield 'android' => [
            [
                RequestOptions::HEADERS => ['User-Agent' => ANDROID_USER_AGENT],
            ],
            'android://foo/bar',
        ];
        yield 'ios' => [
            [
                RequestOptions::HEADERS => ['User-Agent' => IOS_USER_AGENT],
            ],
            'fb://profile/33138223345',
        ];
        yield 'desktop' => [
            [
                RequestOptions::HEADERS => ['User-Agent' => DESKTOP_USER_AGENT],
            ],
            'https://blog.alejandrocelaya.com/2017/12/09/acmailer-7-0-the-most-important-release-in-a-long-time/',
        ];
        yield 'unknown' => [
            [],
            'https://blog.alejandrocelaya.com/2017/12/09/acmailer-7-0-the-most-important-release-in-a-long-time/',
        ];
        yield 'rule: english and foo' => [
            [
                RequestOptions::HEADERS => ['Accept-Language' => 'en-UK'],
                RequestOptions::QUERY => ['foo' => 'bar'],
            ],
            'https://example.com/english-and-foo-query?foo=bar',
        ];
        yield 'rule: multiple query params' => [
            [
                RequestOptions::QUERY => ['foo' => 'bar', 'hello' => 'world'],
            ],
            'https://example.com/multiple-query-params?foo=bar&hello=world',
        ];
        yield 'rule: british english' => [
            [
                RequestOptions::HEADERS => ['Accept-Language' => 'en-UK'],
            ],
            'https://example.com/only-english',
        ];
        yield 'rule: english' => [
            [
                RequestOptions::HEADERS => ['Accept-Language' => 'en'],
            ],
            'https://example.com/only-english',
        ];
        yield 'rule: complex matching accept language' => [
            [
                RequestOptions::HEADERS => ['Accept-Language' => 'fr-FR, es;q=0.9, en;q=0.9, *;q=0.2'],
            ],
            'https://example.com/only-english',
        ];
        yield 'rule: too low quality accept language' => [
            [
                RequestOptions::HEADERS => ['Accept-Language' => 'fr-FR, es;q=0.8, en;q=0.5, *;q=0.2'],
            ],
            'https://blog.alejandrocelaya.com/2017/12/09/acmailer-7-0-the-most-important-release-in-a-long-time/',
        ];
    }

    /**
     * @param non-empty-string $longUrl
     */
    #[Test]
    #[TestWith(['android://foo/bar'])]
    #[TestWith(['fb://profile/33138223345'])]
    #[TestWith(['viber://pa?chatURI=1234'])]
    public function properRedirectHappensForNonHttpLongUrls(string $longUrl): void
    {
        $slug = 'non-http-schema';
        $this->callApiWithKey('POST', '/short-urls', [
            RequestOptions::JSON => [
                'longUrl' => $longUrl,
                'customSlug' => $slug,
            ],
        ]);

        $response = $this->callShortUrl($slug);

        self::assertEquals(302, $response->getStatusCode());
        self::assertEquals($longUrl, $response->getHeaderLine('Location'));
    }
}
