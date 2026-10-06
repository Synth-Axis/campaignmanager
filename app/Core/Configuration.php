<?php

class Configuration
{
    public static function load(string $root): array
    {
        $local = [];
        foreach (['.env', '.ENV'] as $name) {
            $file = $root . '/' . $name;
            if (is_file($file)) {
                $local = parse_ini_file($file, false, INI_SCANNER_RAW);
                if ($local === false) throw new RuntimeException('Invalid local environment file.');
                break;
            }
        }

        // Runtime variables always take precedence, including an empty password.
        $value = static function (string $key, string $default = '', array $aliases = []) use ($local): string {
            foreach (array_merge([$key], $aliases) as $name) {
                $environment = getenv($name);
                if ($environment !== false) return $environment;
            }
            return (string)($local[$key] ?? $default);
        };

        $domain = getenv('RAILWAY_PUBLIC_DOMAIN');
        $address = $value('ADDRESS', $domain ? 'https://' . $domain : 'http://localhost');
        if (getenv('ADDRESS') === false && $domain) $address = 'https://' . $domain;

        return array_replace($local, [
            'APP_ENV' => $value('APP_ENV', 'development'),
            'DB_HOST' => $value('DB_HOST', 'localhost', ['MYSQLHOST']),
            'DB_PORT' => $value('DB_PORT', '3306', ['MYSQLPORT']),
            'DB_NAME' => $value('DB_NAME', 'lynx_app_center', ['MYSQLDATABASE']),
            'DB_USER' => $value('DB_USER', 'root', ['MYSQLUSER']),
            'DB_PASSWORD' => $value('DB_PASSWORD', '', ['MYSQLPASSWORD']),
            'ADDRESS' => rtrim($address, '/'),
            'PHPMAILER_HOST' => $value('PHPMAILER_HOST', 'smtp.example.com'),
            'PHPMAILER_USERNAME' => $value('PHPMAILER_USERNAME'),
            'PHPMAILER_PASSWORD' => $value('PHPMAILER_PASSWORD'),
            'PHPMAILER_PORT' => $value('PHPMAILER_PORT', '587'),
            'PHPMAILER_FROM_EMAIL' => $value('PHPMAILER_FROM_EMAIL', 'LynxApp@lynx.com'),
            'PHPMAILER_FROM_NAME' => $value('PHPMAILER_FROM_NAME', 'LynxApp'),
        ]);
    }
}
