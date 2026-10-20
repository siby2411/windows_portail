<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class CustomerController
{
    public function index(): string
    {
        $customers = Database::fetchAll("SELECT * FROM customers ORDER BY name ASC");

        extract([
            'customers' => $customers,
            'title' => 'Gestion des Clients & Fidélité'
        ]);

        ob_start();
        require APP_PATH . '/Views/customers/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $name = $_POST['name'] ?? null;
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';

        if ($name) {
            Database::query("
                INSERT INTO customers (name, phone, email, loyalty_points)
                VALUES (?, ?, ?, 0)
            ", [$name, $phone, $email]);
        }

        redirect('/customers');
    }
}