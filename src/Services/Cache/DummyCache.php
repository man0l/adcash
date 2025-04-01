<?php

namespace App\Services\Cache;

class DummyCache implements CacheInterface
{
    public function get(string $key, $default = null)
    {
        return $default;
    }

    public function set(string $key, $value, ?int $ttl = null): bool
    {
        return true;
    }

    public function has(string $key): bool
    {
        return false;
    }

    public function delete(string $key): bool
    {
        return true;
    }

    public function clear(): bool
    {
        return true;
    }
} 