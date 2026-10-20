<?php
namespace App\Controllers;
use App\Core\Database;

class OrderController {
    public function index() {
        $orders = Database::fetchAll("SELECT o.*, t.table_number FROM orders o LEFT JOIN restaurant_tables t ON t.id = o.table_id ORDER BY o.id DESC");
        ob_start();
        echo "<h3>Gestion des Commandes</h3><table class='table'><tr><th>N°</th><th>Type</th><th>Total</th><th>Statut</th></tr>";
        foreach($orders as $o) { echo "<tr><td>{$o['order_number']}</td><td>{$o['type']}</td><td>{$o['total']} FCFA</td><td>{$o['status']}</td></tr>"; }
        echo "</table>";
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }
    public function updateStatus(int $id) { redirect('/orders'); }
}