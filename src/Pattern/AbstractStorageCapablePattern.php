<?php

declare(strict_types=1);

namespace Laminas\Cache\Pattern;

use Laminas\Cache\Storage\Adapter\AdapterOptions;
use Laminas\Cache\Storage\StorageInterface;

abstract class AbstractStorageCapablePattern extends AbstractPattern implements StorageCapableInterface
{
    /**
     * @param StorageInterface<AdapterOptions> $storage
     */
    public function __construct(protected StorageInterface $storage, ?PatternOptions $options = null)
    {
        parent::__construct($options);
    }

    /**
     * @return StorageInterface<AdapterOptions>
     */
    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }
}
