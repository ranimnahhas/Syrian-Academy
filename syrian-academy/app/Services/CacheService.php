<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    private const DEFAULT_TTL = 600; // 10 دقائق

    public function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        return Cache::remember($key, $ttl ?? self::DEFAULT_TTL, $callback);
    }

    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    public function flush(): void
    {
        Cache::flush();
    }
}