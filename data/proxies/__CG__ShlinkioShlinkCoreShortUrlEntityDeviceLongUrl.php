<?php

namespace DoctrineProxies\__CG__\Shlinkio\Shlink\Core\ShortUrl\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class DeviceLongUrl extends \Shlinkio\Shlink\Core\ShortUrl\Entity\DeviceLongUrl implements \Doctrine\ORM\Proxy\InternalProxy
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
        "\0".parent::class."\0".'longUrl' => [parent::class, 'longUrl', null],
        "\0".parent::class."\0".'shortUrl' => [parent::class, 'shortUrl', parent::class],
        'deviceType' => [parent::class, 'deviceType', parent::class],
        'id' => [parent::class, 'id', null],
        'longUrl' => [parent::class, 'longUrl', null],
        'shortUrl' => [parent::class, 'shortUrl', parent::class],
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
