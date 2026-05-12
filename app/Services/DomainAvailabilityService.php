<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class DomainAvailabilityService
{
    /**
     * @return array{available: bool, suggestions: list<string>}
     */
    public function check(string $fqdn): array
    {
        $key = 'domain_check:'.strtolower($fqdn);

        return Cache::remember($key, 60, function () use ($fqdn) {
            $hash = crc32(strtolower($fqdn));
            $available = ($hash % 5) !== 0;
            $base = preg_replace('/^([^.]+)\..*$/', '$1', $fqdn) ?: 'brand';
            $suggestions = [
                $base.'-online'.strstr($fqdn, '.'),
                $base.'-zw'.strstr($fqdn, '.'),
                $base.'1'.strstr($fqdn, '.'),
            ];

            return [
                'available' => $available,
                'suggestions' => $suggestions,
            ];
        });
    }
}
