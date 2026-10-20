<?php
declare(strict_types=1);

// ============================================================
// CHARGEUR D'ENVIRONNEMENT (.env)
// ============================================================
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            [$name, $value] = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }
}

// ============================================================
// BASE DE DONNÉES
// ============================================================
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'omega_restaurant');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// ============================================================
// CHEMINS ABSOLUS (SERVEUR)
// ============================================================
define('ROOT_PATH',   __DIR__);
define('APP_PATH',    ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// ============================================================
// BASE URL PATH (POUR LES LIENS HTML / REDIRECTIONS)
// ============================================================
// Forcé à '/restaurant' pour correspondre exactement à votre Alias Apache
 
// Remplacez '/restaurant' par '/omega-restaurant/public'

 

define('BASE_URL_PATH', '/omega-restaurant/public');



// ============================================================
// CONSTANTES APPLICATIVES
// ============================================================
define('CURRENCY', $_ENV['CURRENCY'] ?? 'FCFA');
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Africa/Dakar');