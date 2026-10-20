<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class IngredientController
{
    public function index(): string
    {
        $ingredients = Database::fetchAll("SELECT * FROM ingredients ORDER BY name ASC");

        extract([
            'ingredients' => $ingredients,
            'title' => 'Gestion des Ingrédients'
        ]);

        ob_start();
        require APP_PATH . '/Views/ingredients/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function store(): void
    {
        $name = $_POST['name'] ?? null;
        $unit = $_POST['unit'] ?? 'kg';
        $alertQty = $_POST['alert_qty'] ?? 5.00;
        $unitCost = $_POST['unit_cost'] ?? 0.00;

        if ($name) {
            Database::query("
                INSERT INTO ingredients (name, unit, stock_qty, alert_qty, unit_cost)
                VALUES (?, ?, 0.00, ?, ?)
            ", [$name, $unit, $alertQty, $unitCost]);
        }

        redirect('/ingredients');
    }
}