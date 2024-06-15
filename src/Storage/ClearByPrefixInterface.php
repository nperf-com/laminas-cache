<?php

namespace Laminas\Cache\Storage;

interface ClearByPrefixInterface
{
    /**
     * Remove items matching given prefix
     *
     * @param non-empty-string $prefix
     */
    public function clearByPrefix(string $prefix): bool;
}
