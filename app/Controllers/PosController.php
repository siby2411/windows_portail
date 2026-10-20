<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class PosController
{
    public function index(): string
    {
        $categories = Database::fetchAll("SELECT * FROM categories ORDER BY name ASC");
        $menuItems = Database::fetchAll("SELECT m.*, c.name as category_name FROM menu_items m JOIN categories c ON c.id = m.category_id WHERE m.is_available = 1");
        $tables = Database::fetchAll("SELECT * FROM restaurant_tables ORDER BY table_number ASC");
        $customers = Database::fetchAll("SELECT * FROM customers ORDER BY name ASC");

        // Caisse active
        $activeSession = Database::fetch("SELECT * FROM cash_sessions WHERE user_id = ? AND status = 'open'", [$_SESSION['user_id'] ?? 1]);

        extract([
            'categories' => $categories,
            'menuItems' => $menuItems,
            'tables' => $tables,
            'customers' => $customers,
            'activeSession' => $activeSession,
            'title' => 'Terminal POS - Caisse'
        ]);

        ob_start();
        require APP_PATH . '/Views/pos/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function storeOrder(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || empty($input['items'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Panier vide.']);
            exit;
        }

        $orderNumber = 'CMD-' . date('Ymd') . '-' . rand(1000, 9999);
        $total = (float)$input['total'];
        $paid = (float)($input['paid_amount'] ?? $total);
        $change = $paid - $total;

        Database::query("
            INSERT INTO orders (order_number, table_id, customer_id, user_id, type, status, payment_method, subtotal, total, paid_amount, change_amount)
            VALUES (?, ?, ?, ?, ?, 'paid', ?, ?, ?, ?, ?)
        ", [
            $orderNumber,
            $input['table_id'] ?? null,
            $input['customer_id'] ?? null,
            $_SESSION['user_id'] ?? 1,
            $input['type'] ?? 'dine_in',
            $input['payment_method'] ?? 'cash',
            $total,
            $total,
            $paid,
            $change
        ]);

        $orderId = Database::lastInsertId();

        // Insertion des articles et Déstockage Automatique des ingrédients
        foreach ($input['items'] as $item) {
            Database::query("
                INSERT INTO order_items (order_id, menu_item_id, unit_price, quantity, total_price)
                VALUES (?, ?, ?, ?, ?)
            ", [$orderId, $item['id'], $item['price'], $item['quantity'], $item['price'] * $item['quantity']]);

            // Déstockage via Fiche Technique (Recipes)
            $recipes = Database::fetchAll("SELECT * FROM recipes WHERE menu_item_id = ?", [$item['id']]);
            foreach ($recipes as $recipe) {
                $deductQty = $recipe['quantity_needed'] * $item['quantity'];
                Database::query("
                    UPDATE ingredients SET stock_qty = stock_qty - ? WHERE id = ?
                ", [$deductQty, $recipe['ingredient_id']]);
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'order_id' => $orderId, 'order_number' => $orderNumber]);
        exit;
    }
}