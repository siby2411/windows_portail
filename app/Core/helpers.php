<?php
declare(strict_types=1);

/**
 * Génère une URL absolue propre pour l'application en tenant compte de l'Alias /restaurant
 */
if (!function_exists('url')) {
    function url(string $path = ''): string {
        $baseUrl = rtrim(BASE_URL_PATH, '/');
        $path = '/' . ltrim($path, '/');
        if ($path === '/') {
            $path = '';
        }
        return $baseUrl . $path;
    }
}

/**
 * Échappement sécurisé pour le HTML
 */
if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/**
 * Redirection HTTP propre
 */
if (!function_exists('redirect')) {
    function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }
}

/**
 * Génère le chemin vers les assets (CSS, JS, images)
 */
if (!function_exists('asset')) {
    function asset(string $path): string {
        return url(ltrim($path, '/'));
    }
}






/**
 * Formate un montant en prix (ex: 5000 -> 5 000 FCFA)
 */
if (!function_exists('formatPrice')) {
    function formatPrice(float|int|null $amount): string {
        if ($amount === null) {
            $amount = 0;
        }
        return number_format((float)$amount, 0, ',', ' ') . ' ' . (defined('CURRENCY') ? CURRENCY : 'FCFA');
    }
}







