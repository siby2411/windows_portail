<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class ReservationController
{
    public function index(): string
    {
        $reservations = Database::fetchAll("
            SELECT r.*, c.name as customer_name, t.table_number 
            FROM reservations r 
            JOIN customers c ON c.id = r.customer_id 
            LEFT JOIN restaurant_tables t ON t.id = r.table_id 
            ORDER BY r.reservation_time DESC
        ");
        $customers = Database::fetchAll("SELECT * FROM customers ORDER BY name ASC");
        $tables = Database::fetchAll("SELECT * FROM restaurant_tables ORDER BY table_number ASC");

        extract([
            'reservations' => $reservations,
            'customers' => $customers,
            'tables' => $tables,
            'title' => 'Gestion des Réservations'
        ]);

        ob_start();
        require APP_PATH . '/Views/reservations/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $customerId = $_POST['customer_id'] ?? null;
        $tableId = $_POST['table_id'] ?? null;
        $time = $_POST['reservation_time'] ?? null;
        $guests = $_POST['guests_count'] ?? 2;
        $notes = $_POST['notes'] ?? '';

        if ($customerId && $time) {
            Database::query("
                INSERT INTO reservations (customer_id, table_id, reservation_time, guests_count, status, notes)
                VALUES (?, ?, ?, ?, 'confirmed', ?)
            ", [$customerId, $tableId ?: null, $time, $guests, $notes]);
        }

        redirect('/reservations');
    }
}