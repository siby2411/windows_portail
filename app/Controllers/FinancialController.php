<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class FinancialController
{
    public function index(): string
    {
        $expenses = Database::fetchAll("SELECT * FROM expenses ORDER BY expense_date DESC");
        $totalExpenses = Database::fetch("SELECT SUM(amount) as total FROM expenses")['total'] ?? 0;
        $totalRevenue = Database::fetch("SELECT SUM(total) as total FROM orders WHERE status = 'paid'")['total'] ?? 0;

        extract([
            'expenses' => $expenses,
            'totalExpenses' => (float)$totalExpenses,
            'totalRevenue' => (float)$totalRevenue,
            'title' => 'Finances & Dépenses'
        ]);

        ob_start();
        require APP_PATH . '/Views/financial/index.php';
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }

    public function storeExpense(): void
    {
        $title = $_POST['title'] ?? null;
        $category = $_POST['category'] ?? 'Général';
        $amount = (float)($_POST['amount'] ?? 0);
        $date = $_POST['expense_date'] ?? date('Y-m-d');
        $notes = $_POST['notes'] ?? '';

        if ($title && $amount > 0) {
            Database::query("
                INSERT INTO expenses (title, category, amount, expense_date, notes)
                VALUES (?, ?, ?, ?, ?)
            ", [$title, $category, $amount, $date, $notes]);
        }

        redirect('/financial');
    }
}