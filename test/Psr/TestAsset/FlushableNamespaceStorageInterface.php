<?php

declare(strict_types=1);

namespace LaminasTest\Cache\Psr\TestAsset;

use Laminas\Cache\Storage\Adapter\AdapterOptions;
use Laminas\Cache\Storage\ClearByNamespaceInterface;

/**
 * @template TOptions of AdapterOptions
 * @template-extends FlushableStorageInterface<TOptions>
 */
interface FlushableNamespaceStorageInterface extends FlushableStorageInterface, ClearByNamespaceInterface
{
}
