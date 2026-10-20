<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class MenuController
{
    public function index(): string
    {
        $menuItems = Database::fetchAll("
            SELECT m.*, c.name as category_name 
            FROM menu_items m 
            JOIN categories c ON c.id = m.category_id 
            ORDER BY c.name ASC, m.name ASC
        ");
        $categories = Database::fetchAll("SELECT * FROM categories ORDER BY name ASC");

        extract([
            'menuItems' => $menuItems,
            'categories' => $categories,
            'title' => 'Gestion de la Carte & Menu'
        ]);

        ob_start();
        require APP_PATH . '/Views/menu/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $categoryId = $_POST['category_id'] ?? null;
        $name = $_POST['name'] ?? null;
        $price = $_POST['price'] ?? 0;
        $description = $_POST['description'] ?? '';

        if ($name && $categoryId && $price) {
            Database::query("
                INSERT INTO menu_items (category_id, name, description, price, is_available)
                VALUES (?, ?, ?, ?, 1)
            ", [$categoryId, $name, $description, $price]);
        }

        redirect('/menu');
    }
}