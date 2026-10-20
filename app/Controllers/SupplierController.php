<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class SupplierController
{
    public function index(): string
    {
        $suppliers = Database::fetchAll("SELECT * FROM suppliers ORDER BY company_name ASC");

        extract([
            'suppliers' => $suppliers,
            'title' => 'Gestion des Fournisseurs'
        ]);

        ob_start();
        require APP_PATH . '/Views/suppliers/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $companyName = $_POST['company_name'] ?? null;
        $contactName = $_POST['contact_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';

        if ($companyName) {
            Database::query("
                INSERT INTO suppliers (company_name, contact_name, phone, email, address)
                VALUES (?, ?, ?, ?, ?)
            ", [$companyName, $contactName, $phone, $email, $address]);
        }

        redirect('/suppliers');
    }
}