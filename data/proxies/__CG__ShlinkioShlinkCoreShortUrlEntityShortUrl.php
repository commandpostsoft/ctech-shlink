<?php

namespace DoctrineProxies\__CG__\Shlinkio\Shlink\Core\ShortUrl\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ShortUrl extends \Shlinkio\Shlink\Core\ShortUrl\Entity\ShortUrl implements \Doctrine\ORM\Proxy\InternalProxy
{
    use \Symfony\Component\VarExporter\LazyGhostTrait {
        initializeLazyObject as __load;
        setLazyObjectAsInitialized as public __setInitialized;
        isLazyObjectInitialized as private;
        createLazyGhost as private;
        resetLazyObject as private;
    }

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".'*'."\0".'id' => [parent::class, 'id', null],
        "\0".parent::class."\0".'authorApiKey' => [parent::class, 'authorApiKey', null],
        "\0".parent::class."\0".'crawlable' => [parent::class, 'crawlable', null],
        "\0".parent::class."\0".'customSlugWasProvided' => [parent::class, 'customSlugWasProvided', null],
        "\0".parent::class."\0".'dateCreated' => [parent::class, 'dateCreated', null],
        "\0".parent::class."\0".'domain' => [parent::class, 'domain', null],
        "\0".parent::class."\0".'forwardQuery' => [parent::class, 'forwardQuery', null],
        "\0".parent::class."\0".'importOriginalShortCode' => [parent::class, 'importOriginalShortCode', null],
        "\0".parent::class."\0".'importSource' => [parent::class, 'importSource', null],
        "\0".parent::class."\0".'longUrl' => [parent::class, 'longUrl', null],
        "\0".parent::class."\0".'maxVisits' => [parent::class, 'maxVisits', null],
        "\0".parent::class."\0".'shortCode' => [parent::class, 'shortCode', null],
        "\0".parent::class."\0".'shortCodeLength' => [parent::class, 'shortCodeLength', null],
        "\0".parent::class."\0".'tags' => [parent::class, 'tags', null],
        "\0".parent::class."\0".'title' => [parent::class, 'title', null],
        "\0".parent::class."\0".'titleWasAutoResolved' => [parent::class, 'titleWasAutoResolved', null],
        "\0".parent::class."\0".'validSince' => [parent::class, 'validSince', null],
        "\0".parent::class."\0".'validUntil' => [parent::class, 'validUntil', null],
        "\0".parent::class."\0".'visits' => [parent::class, 'visits', null],
        'authorApiKey' => [parent::class, 'authorApiKey', null],
        'crawlable' => [parent::class, 'crawlable', null],
        'customSlugWasProvided' => [parent::class, 'customSlugWasProvided', null],
        'dateCreated' => [parent::class, 'dateCreated', null],
        'domain' => [parent::class, 'domain', null],
        'forwardQuery' => [parent::class, 'forwardQuery', null],
        'id' => [parent::class, 'id', null],
        'importOriginalShortCode' => [parent::class, 'importOriginalShortCode', null],
        'importSource' => [parent::class, 'importSource', null],
        'longUrl' => [parent::class, 'longUrl', null],
        'maxVisits' => [parent::class, 'maxVisits', null],
        'shortCode' => [parent::class, 'shortCode', null],
        'shortCodeLength' => [parent::class, 'shortCodeLength', null],
        'tags' => [parent::class, 'tags', null],
        'title' => [parent::class, 'title', null],
        'titleWasAutoResolved' => [parent::class, 'titleWasAutoResolved', null],
        'validSince' => [parent::class, 'validSince', null],
        'validUntil' => [parent::class, 'validUntil', null],
        'visits' => [parent::class, 'visits', null],
    ];

    public function __isInitialized(): bool
    {
        return isset($this->lazyObjectState) && $this->isLazyObjectInitialized();
    }

    public function __serialize(): array
    {
        $properties = (array) $this;
        unset($properties["\0" . self::class . "\0lazyObjectState"]);

        return $properties;
    }
}
