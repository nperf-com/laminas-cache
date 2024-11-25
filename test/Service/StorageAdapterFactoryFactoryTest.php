<?php

declare(strict_types=1);

namespace LaminasTest\Cache\Service;

use Laminas\Cache\Service\StorageAdapterFactoryFactory;
use Laminas\Cache\Service\StoragePluginFactoryInterface;
use Laminas\Cache\Storage\AdapterPluginManager;
use Laminas\ServiceManager\PluginManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class StorageAdapterFactoryFactoryTest extends TestCase
{
    private StorageAdapterFactoryFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new StorageAdapterFactoryFactory();
    }

    public function testWillRetrieveDependenciesFromContainer(): void
    {
        $adapters      = $this->createMock(PluginManagerInterface::class);
        $pluginFactory = $this->createMock(StoragePluginFactoryInterface::class);
        $container     = $this->createMock(ContainerInterface::class);
        $invokedCount  = self::exactly(2);
        $container
            ->expects($invokedCount)
            ->method('get')
            ->with(self::callback(static function (string $arg) use ($invokedCount): bool {
                switch ($invokedCount->numberOfInvocations()) {
                    case 1:
                        self::assertSame(AdapterPluginManager::class, $arg);
                        return true;
                    case 2:
                        self::assertSame(StoragePluginFactoryInterface::class, $arg);
                        return true;
                    default:
                        return false;
                }
            }))
            ->willReturnOnConsecutiveCalls($adapters, $pluginFactory);

        ($this->factory)($container);
    }
}
