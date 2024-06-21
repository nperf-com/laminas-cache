<?php

declare(strict_types=1);

namespace LaminasTest\Cache\Psr\TestAsset;

use Laminas\Cache\Storage\Adapter\AdapterOptions;
use Laminas\Cache\Storage\FlushableInterface;
use Laminas\Cache\Storage\PluginAwareInterface;
use Laminas\Cache\Storage\StorageInterface;

/**
 * @template TOptions of AdapterOptions
 * @template-extends StorageInterface<TOptions>
 */
interface FlushableStorageInterface extends StorageInterface, FlushableInterface, PluginAwareInterface
{
}
