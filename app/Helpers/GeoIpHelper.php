<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoIpHelper
{
    /**
     * Parse device type from User Agent
     */
    public static function getDeviceType(?string $userAgent): string
    {
        if (empty($userAgent)) return 'Desktop';

        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $userAgent)) {
            return 'Tablet';
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iphone|ipod|blackberry|iemobile)/i', $userAgent)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    /**
     * Parse Operating System from User Agent
     */
    public static function getOs(?string $userAgent): string
    {
        if (empty($userAgent)) return 'Unknown OS';

        if (str_contains($userAgent, 'Windows NT 10.0')) return 'Windows 10/11';
        if (str_contains($userAgent, 'Windows NT 6.3')) return 'Windows 8.1';
        if (str_contains($userAgent, 'Windows NT 6.2')) return 'Windows 8';
        if (str_contains($userAgent, 'Windows NT 6.1')) return 'Windows 7';
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'iPhone')) return 'iOS (iPhone)';
        if (str_contains($userAgent, 'iPad')) return 'iPadOS';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'Macintosh') || str_contains($userAgent, 'Mac OS X')) return 'macOS';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        return 'Unknown OS';
    }

    /**
     * Parse Browser name from User Agent
     */
    public static function getBrowser(?string $userAgent): string
    {
        if (empty($userAgent)) return 'Unknown Browser';

        if (str_contains($userAgent, 'Edg')) return 'Microsoft Edge';
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) return 'Google Chrome';
        if (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) return 'Safari';
        if (str_contains($userAgent, 'Firefox')) return 'Mozilla Firefox';
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) return 'Opera';
        return 'Web Browser';
    }

    /**
     * Resolve City, Country from IP address (Cached for 24 hours)
     */
    public static function getLocation(?string $ip): string
    {
        if (empty($ip)) return 'Unknown Location';

        // Check for local or private IP ranges
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.16.')) {
            return 'Localhost / Office LAN';
        }

        // Check Cloudflare header if available
        $cfCountry = request()->header('CF-IPCountry');
        if (!empty($cfCountry) && $cfCountry !== 'XX') {
            $countryName = class_exists('\Locale') ? \Locale::getDisplayRegion('-' . $cfCountry, 'en') : $cfCountry;
            return $countryName ?: $cfCountry;
        }

        return Cache::remember('geoip_' . md5($ip), 86400, function () use ($ip) {
            try {
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,country,city,regionName");
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['status']) && $data['status'] === 'success') {
                        $city = $data['city'] ?? '';
                        $country = $data['country'] ?? '';
                        if ($city && $country) {
                            return "{$city}, {$country}";
                        }
                        return $country ?: ($city ?: 'Unknown Location');
                    }
                }
            } catch (\Throwable $e) {
                // Ignore timeout or network errors
            }

            return 'Pakistan (Default)';
        });
    }
}
