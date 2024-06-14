<?php

namespace Laminas\Cache;

final class Module
{
    /**
     * Return default laminas-cache configuration for laminas-mvc context.
     */
    public function getConfig(): array
    {
        $provider = new ConfigProvider();
        return [
            'service_manager' => $provider->getDependencyConfig(),
        ];
    }
}
