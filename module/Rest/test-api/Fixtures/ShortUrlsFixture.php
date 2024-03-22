<?php

declare(strict_types=1);

namespace ShlinkioApiTest\Shlink\Rest\Fixtures;

use Cake\Chronos\Chronos;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionObject;
use Shlinkio\Shlink\Core\ShortUrl\Entity\ShortUrl;
use Shlinkio\Shlink\Core\ShortUrl\Model\ShortUrlCreation;
use Shlinkio\Shlink\Core\ShortUrl\Resolver\PersistenceShortUrlRelationResolver;
use Shlinkio\Shlink\Rest\Entity\ApiKey;

class ShortUrlsFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [ApiKeyFixture::class, TagsFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        $relationResolver = new PersistenceShortUrlRelationResolver($manager); // @phpstan-ignore-line

        /** @var ApiKey $authorApiKey */
        $authorApiKey = $this->getReference('author_api_key');

        $abcShortUrl = $this->setShortUrlDate(
            ShortUrl::create(ShortUrlCreation::fromRawData([
                'customSlug' => 'abc123',
                'apiKey' => $authorApiKey,
                'longUrl' => 'https://shlink.io',
                'tags' => ['foo'],
                'title' => 'My cool title',
                'crawlable' => true,
                'maxVisits' => 2,
            ]), $relationResolver),
            '2018-05-01',
        );
        $manager->persist($abcShortUrl);

        $defShortUrl = $this->setShortUrlDate(ShortUrl::create(ShortUrlCreation::fromRawData([
            'validSince' => Chronos::parse('2020-05-01'),
            'customSlug' => 'def456',
            'apiKey' => $authorApiKey,
            'longUrl' =>
                'https://blog.alejandrocelaya.com/2017/12/09/acmailer-7-0-the-most-important-release-in-a-long-time/',
            'tags' => ['foo', 'bar'],
        ]), $relationResolver), '2019-01-01 00:00:10');
        $manager->persist($defShortUrl);

        $customShortUrl = $this->setShortUrlDate(ShortUrl::create(ShortUrlCreation::fromRawData([
            'customSlug' => 'custom',
            'maxVisits' => 2,
            'apiKey' => $authorApiKey,
            'longUrl' => 'https://shlink.io',
            'crawlable' => true,
            'forwardQuery' => false,
        ])), '2019-01-01 00:00:20');
        $manager->persist($customShortUrl);

        $ghiShortUrl = $this->setShortUrlDate(
            ShortUrl::create(ShortUrlCreation::fromRawData([
                'customSlug' => 'ghi789',
                'longUrl' => 'https://shlink.io/documentation/',
                'validUntil' => Chronos::parse('2020-05-01'), // In the past
            ])),
            '2018-05-01',
        );
        $manager->persist($ghiShortUrl);

        $withDomainDuplicatingShortCode = $this->setShortUrlDate(ShortUrl::create(ShortUrlCreation::fromRawData([
            'domain' => 'example.com',
            'customSlug' => 'ghi789',
            'longUrl' => 'https://blog.alejandrocelaya.com/2019/04/27/considerations-to-properly-use-open-'
                . 'source-software-projects/',
            'tags' => ['foo'],
        ]), $relationResolver), '2019-01-01 00:00:30');
        $manager->persist($withDomainDuplicatingShortCode);

        $withDomainAndSlugShortUrl = $this->setShortUrlDate(ShortUrl::create(ShortUrlCreation::fromRawData(
            ['domain' => 'some-domain.com', 'customSlug' => 'custom-with-domain', 'longUrl' => 'https://google.com'],
        )), '2018-10-20');
        $manager->persist($withDomainAndSlugShortUrl);

        $manager->flush();

        $this->addReference('abc123_short_url', $abcShortUrl);
        $this->addReference('def456_short_url', $defShortUrl);
        $this->addReference('ghi789_short_url', $ghiShortUrl);
        $this->addReference('example_short_url', $withDomainDuplicatingShortCode);
    }

    private function setShortUrlDate(ShortUrl $shortUrl, string $date): ShortUrl
    {
        $ref = new ReflectionObject($shortUrl);
        $dateProp = $ref->getProperty('dateCreated');
        $dateProp->setAccessible(true);
        $dateProp->setValue($shortUrl, Chronos::parse($date));

        return $shortUrl;
    }
}
