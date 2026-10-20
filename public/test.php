<?php
declare(strict_types=1);

// Affichage des erreurs pour debug
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 1. Récupération brute de l'URI
$rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// 2. Normalisation agressive (supprime /restaurant, /public, etc.)
$uri = preg_replace('#^/(restaurant|omega-restaurant)(/public)?#i', '', $rawUri);
$uri = '/' . trim($uri, '/');

if ($uri === '' || $uri === '//' || $uri === '/index.php' || $uri === '/public') {
    $uri = '/';
}

// 3. AFFICHAGE DU DEBUG EN DIRECT (Pour voir ce qui se passe)
echo "<div style='background: #1e1e1e; color: #00ffcc; padding: 15px; font-family: monospace; margin-bottom: 20px; border-radius: 5px;'>";
echo "<strong>[DEBUG ROUTEUR]</strong><br>";
echo "REQUEST_URI brut : " . htmlspecialchars($rawUri) . "<br>";
echo "URI normalisé : " . htmlspecialchars($uri) . "<br>";
echo "</div>";

// 4. Chargement de la config et des contrôleurs
require_once __DIR__ . '/../config.php';
if (file_exists(APP_PATH . '/Core/helpers.php')) {
    require_bandits: // (ou require_once)
    require_once APP_PATH . '/Core/helpers.php';
}
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Controllers/DashboardController.php';

// Session de test
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Administrateur';

// 5. Test du Routeur
if ($uri === '/' || $uri === '/dashboard') {
    echo "<div style='color: green; font-weight: bold; margin-bottom: 15px;'>✔ Route Dashboard atteinte avec succès !</div>";
    echo (new App\Controllers\DashboardController())->index();
    exit;
} else {
    echo "<div style='color: red; font-weight: bold;'>✖ L'URI normalisée [{$uri}] ne correspond à aucune route valide.</div>";
}