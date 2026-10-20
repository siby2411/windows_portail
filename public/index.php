<?php
declare(strict_types=1);

// 1. Gestion des erreurs
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 2. Chargement de l'application
require_once __DIR__ . '/../config.php';

if (file_exists(APP_PATH . '/Core/helpers.php')) {
    require_once APP_PATH . '/Core/helpers.php';
}
require_once APP_PATH . '/Core/Database.php';

// Chargement de tous les contrôleurs
require_once APP_PATH . '/Controllers/DashboardController.php';
require_once APP_PATH . '/Controllers/PosController.php';
require_once APP_PATH . '/Controllers/OrderController.php';
require_once APP_PATH . '/Controllers/ReservationController.php';
require_once APP_PATH . '/Controllers/MenuController.php';
require_once APP_PATH . '/Controllers/IngredientController.php';
require_once APP_PATH . '/Controllers/StockController.php';
require_once APP_PATH . '/Controllers/SupplierController.php';
require_once APP_PATH . '/Controllers/CustomerController.php';
require_once APP_PATH . '/Controllers/FinancialController.php';

// 3. Normalisation ultra-précise de l'URI
$rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Liste des préfixes à retirer s'ils se trouvent au début de l'URL
$basePaths = [
    '/omega-restaurant/public',
    '/omega-restaurant',
    '/restaurant/public',
    '/restaurant',
    '/index.php'
];

$uri = $rawUri;
foreach ($basePaths as $bp) {
    if (str_starts_with($uri, $bp)) {
        $uri = substr($uri, strlen($bp));
        break;
    }
}

$uri = '/' . trim($uri, '/');
if ($uri === '//' || $uri === '') {
    $uri = '/';
}

// 4. Session de dev
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Administrateur';
    $_SESSION['role'] = 'admin';
}

// 5. Routeur Principal
switch (true) {
    case ($uri === '/' || $uri === '/dashboard'):
        echo (new App\Controllers\DashboardController())->index();
        break;

    case ($uri === '/pos'):
        echo (new App\Controllers\PosController())->index();
        break;

    case ($uri === '/orders'):
        echo (new App\Controllers\OrderController())->index();
        break;

    case ($uri === '/reservations'):
        echo (new App\Controllers\ReservationController())->index();
        break;

    case ($uri === '/menu'):
        echo (new App\Controllers\MenuController())->index();
        break;

    case ($uri === '/ingredients'):
        echo (new App\Controllers\IngredientController())->index();
        break;

    case ($uri === '/stock'):
        echo (new App\Controllers\StockController())->index();
        break;

    case ($uri === '/suppliers'):
        echo (new App\Controllers\SupplierController())->index();
        break;

    case ($uri === '/customers'):
        echo (new App\Controllers\CustomerController())->index();
        break;

    case ($uri === '/financial'):
        echo (new App\Controllers\FinancialController())->index();
        break;

    default:
        http_response_code(404);
        echo "<div style='font-family: system-ui, sans-serif; padding: 50px; text-align: center; max-width: 600px; margin: 50px auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); background: #fff;'>";
        echo "<h1 style='color: #dc3545; margin-bottom: 20px;'>404 - Page non trouvée</h1>";
        echo "<p style='color: #6c757d; margin-bottom: 20px;'>L'adresse demandée (URI nettoyée) : <code style='background: #f8f9fa; padding: 4px 8px; border-radius: 4px; color: #d63384;'>" . htmlspecialchars($uri) . "</code></p>";
        echo "<p style='color: #6c757d; margin-bottom: 20px;'>Requête brute : <code>" . htmlspecialchars($rawUri) . "</code></p>";
        echo "<a href='" . url('/') . "' style='display: inline-block; padding: 10px 20px; background-color: #0d6efd; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold;'>← Retour au Dashboard</a>";
        echo "</div>";
        break;
}