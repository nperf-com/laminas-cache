<?php

namespace Laminas\Cache;

final class Module
{
    public function getConfig(): array
    {
        $config                    = (new ConfigProvider())();
        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);
        return $config;
    }
}
