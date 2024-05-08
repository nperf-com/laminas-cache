# Storage Capabilities

Storage capabilities describe how a storage adapter works, and which features it
supports.

To get capabilities of a storage adapter, you can use the method
`getCapabilities()`, but only the storage adapter and its plugins have
permissions to change them.

Because capabilities are mutable, you can subscribe to the "change" event to get
notifications; see the examples for details.

If you are writing your own plugin or adapter, you can also change capabilities
because you have access to the marker object and can create your own marker to
instantiate a new instance of `Laminas\Cache\Storage\Capabilities`.

## Available Methods

```php
namespace Laminas\Cache\Storage;

use ArrayObject;
use stdClass;
use Laminas\Cache\Exception;
use Laminas\EventManager\EventsCapableInterface;

class Capabilities
{
    /**
     * Constructor
     *
     */
    public function __construct(
        StorageInterface $storage,
        stdClass $marker,
        array $capabilities = [],
        Capabilities|null $baseCapabilities = null
    );

    /**
     * Get the storage adapter
     */
    public function getAdapter(): StorageInterface;

    /**
     * Get supported datatypes
     */
    public function getSupportedDatatypes(): array;

    /**
     * Set supported datatypes
     *
     * @param  stdClass $marker
     * @param  array $datatypes
     * @throws Exception\InvalidArgumentException
     * @return Capabilities Fluent interface
     */
    public function setSupportedDatatypes(stdClass $marker, array $datatypes);

    /**
     * Get minimum supported time-to-live
     *
     * @return int 0 means items never expire
     */
    public function getMinTtl(): int;

    /**
     * Set minimum supported time-to-live
     *
     * @param  stdClass $marker
     * @param  int $minTtl
     * @throws Exception\InvalidArgumentException
     */
    public function setMinTtl(stdClass $marker, int $minTtl): self;

    /**
     * Get maximum supported time-to-live
     *
     * @return int 0 means infinite
     */
    public function getMaxTtl(): int;

    /**
     * Set maximum supported time-to-live
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setMaxTtl(stdClass $marker, int $maxTtl): self;

    /**
     * Is the time-to-live handled static (on write)
     * or dynamic (on read)
     */
    public function getStaticTtl(): bool;

    /**
     * Set if the time-to-live handled static (on write) or dynamic (on read)
     */
    public function setStaticTtl(stdClass $marker, bool $flag): self;

    /**
     * Get time-to-live precision
     */
    public function getTtlPrecision(): float;

    /**
     * Set time-to-live precision
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setTtlPrecision(stdClass $marker, float $ttlPrecision): self;

    /**
     * Get use request time
     */
    public function getUseRequestTime(): bool;

    /**
     * Set use request time
     */
    public function setUseRequestTime(stdClass $marker, bool $flag): self;


    /**
     * Get "lock-on-expire" support in seconds.
     *
     * @return int  0 = Expired items will never be retrieved
     *             >0 = Time in seconds an expired item could be retrieved
     *             -1 = Expired items could be retrieved forever
     */
    public function getLockOnExpire(): int
    {
        return $this->getCapability('lockOnExpire', 0);
    }

    /**
     * Set "lock-on-expire" support in seconds.
     */
    public function setLockOnExpire(stdClass $marker, int $timeout): self
    {
        return $this->setCapability($marker, 'lockOnExpire', (int) $timeout);
    }

    /**
     * Get maximum key length
     *
     * @return int -1 means unknown, 0 means infinite
     */
    public function getMaxKeyLength(): int;

    /**
     * Set maximum key length
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setMaxKeyLength(stdClass $marker, int $maxKeyLength): self;

    /**
     * Get if namespace support is implemented as prefix
     */
    public function getNamespaceIsPrefix(): bool;

    /**
     * Set if namespace support is implemented as prefix
     */
    public function setNamespaceIsPrefix(stdClass $marker, bool $flag): self;

    /**
     * Get namespace separator if namespace is implemented as prefix
     */
    public function getNamespaceSeparator(): string;

    /**
     * Set the namespace separator if namespace is implemented as prefix
     */
    public function setNamespaceSeparator(stdClass $marker, string $separator): self;
}
```

## Examples

### Get Storage Capabilities and do specific Stuff based on them

```php
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = null; // can be any configured PSR-11 container

/** @var StorageAdapterFactoryInterface $storageFactory */
$storageFactory = $container->get(StorageAdapterFactoryInterface::class);

$cache = $storageFactory->create('filesystem');
$supportedDatatypes = $cache->getCapabilities()->getSupportedDatatypes();

// now you can run specific stuff in base of supported feature
if ($supportedDatatypes['object']) {
    $cache->set($key, $object);
} else {
    $cache->set($key, serialize($object));
}
```

### Listen to the change Event

```php
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = null; // can be any configured PSR-11 container

/** @var StorageAdapterFactoryInterface $storageFactory */
$storageFactory = $container->get(StorageAdapterFactoryInterface::class);

$cache = $storageFactory->create('filesystem', [
    'no_atime' => false,
]);

// Catching capability changes
$cache->getEventManager()->attach('capability', function($event) {
    echo count($event->getParams()) . ' capabilities changed';
});

// change option which changes capabilities
$cache->getOptions()->setNoATime(true);
```
