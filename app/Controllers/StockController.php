<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class StockController
{
    public function index(): string
    {
        $entries = Database::fetchAll("
            SELECT se.*, i.name as ingredient_name, s.company_name as supplier_name 
            FROM stock_entries se 
            JOIN ingredients i ON i.id = se.ingredient_id 
            LEFT JOIN suppliers s ON s.id = se.supplier_id 
            ORDER BY se.entry_date DESC
        ");
        $ingredients = Database::fetchAll("SELECT * FROM ingredients ORDER BY name ASC");
        $suppliers = Database::fetchAll("SELECT * FROM suppliers ORDER BY company_name ASC");

        extract([
            'entries' => $entries,
            'ingredients' => $ingredients,
            'suppliers' => $suppliers,
            'title' => 'Approvisionnements & Stocks'
        ]);

        ob_start();
        require APP_PATH . '/Views/stock/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function storeEntry(): void
    {
        $ingredientId = $_POST['ingredient_id'] ?? null;
        $supplierId = $_POST['supplier_id'] ?? null;
        $quantity = (float)($_POST['quantity'] ?? 0);
        $unitPrice = (float)($_POST['unit_price'] ?? 0);
        $totalPrice = $quantity * $unitPrice;

        if ($ingredientId && $quantity > 0) {
            // Insertion de l'entrée de stock
            Database::query("
                INSERT INTO stock_entries (ingredient_id, supplier_id, quantity, unit_price, total_price)
                VALUES (?, ?, ?, ?, ?)
            ", [$ingredientId, $supplierId ?: null, $quantity, $unitPrice, $totalPrice]);

            // Mise à jour du stock actuel de l'ingrédient
            Database::query("
                UPDATE ingredients SET stock_qty = stock_qty + ? WHERE id = ?
            ", [$quantity, $ingredientId]);
        }

        redirect('/stock');
    }
}