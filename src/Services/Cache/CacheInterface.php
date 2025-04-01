<?php

namespace App\Services\Cache;

interface CacheInterface
{
    /**
     * Get an item from the cache
     *
     * @param string $key The cache key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Store an item in the cache
     *
     * @param string $key The cache key
     * @param mixed $value The value to cache
     * @param int|null $ttl Time to live in seconds (null = forever)
     * @return bool Success flag
     */
    public function set(string $key, $value, ?int $ttl = null): bool;

    /**
     * Check if key exists in cache
     *
     * @param string $key The cache key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Remove an item from the cache
     *
     * @param string $key The cache key
     * @return bool Success flag
     */
    public function delete(string $key): bool;

    /**
     * Clear the entire cache
     *
     * @return bool Success flag
     */
    public function clear(): bool;
} 