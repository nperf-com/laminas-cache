<?php

namespace Laminas\Cache\Storage;

interface ClearByNamespaceInterface
{
    /**
     * Remove items of given namespace
     *
     * @param non-empty-string $namespace
     */
    public function clearByNamespace(string $namespace): bool;
}
