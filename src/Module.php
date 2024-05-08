<?php

namespace Laminas\Cache;

class Module
{
    /**
     * Return default laminas-cache configuration for laminas-mvc context.
     */
    public function getConfig(): array
    {
        $provider = new ConfigProvider();
        return [
            'service_manager' => $provider->getDependencyConfig(),
            'laminas-cli'     => $provider->getCliConfig(),
        ];
    }
}
