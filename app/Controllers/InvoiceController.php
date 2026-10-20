<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class InvoiceController
{
    public function printTicket(int $orderId): string
    {
        $order = Database::fetch("
            SELECT o.*, u.fullname as cashier_name, t.table_number 
            FROM orders o 
            LEFT JOIN users u ON u.id = o.user_id 
            LEFT JOIN restaurant_tables t ON t.id = o.table_id 
            WHERE o.id = ?
        ", [$orderId]);

        $items = Database::fetchAll("
            SELECT oi.*, m.name 
            FROM order_items oi 
            JOIN menu_items m ON m.id = oi.menu_item_id 
            WHERE oi.order_id = ?
        ", [$orderId]);

        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Ticket #<?= e($order['order_number'] ?? '') ?></title>
            <style>
                body { font-family: 'Courier New', Courier, monospace; width: 80mm; margin: 0 auto; padding: 10px; font-size: 12px; }
                .text-center { text-align: center; }
                .border-top { border-top: 1px dashed #000; margin-top: 8px; padding-top: 8px; }
                .d-flex { display: flex; justify-content: space-between; }
            </style>
        </head>
        <body onload="window.print()">
            <div class="text-center">
                <h3 style="margin:0;">OMEGA RESTAURANT</h3>
                <p style="margin:2px;">Dakar, Sénégal</p>
                <p style="margin:2px;">Tél: +221 33 800 00 00</p>
            </div>

            <div class="border-top">
                <div>Ticket N°: <strong><?= e($order['order_number'] ?? '') ?></strong></div>
                <div>Date: <?= e($order['created_at'] ?? '') ?></div>
                <div>Caissier: <?= e($order['cashier_name'] ?? '') ?></div>
                <div>Table: <?= e($order['table_number'] ?? 'Emporter') ?></div>
            </div>

            <div class="border-top">
                <?php foreach ($items as $item): ?>
                    <div class="d-flex">
                        <span><?= $item['quantity'] ?>x <?= e($item['name']) ?></span>
                        <span><?= formatPrice((float)$item['total_price']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="border-top">
                <div class="d-flex" style="font-size: 14px; font-weight: bold;">
                    <span>TOTAL :</span>
                    <span><?= formatPrice((float)($order['total'] ?? 0)) ?></span>
                </div>
                <div class="d-flex">
                    <span>Paiement:</span>
                    <span><?= strtoupper(e($order['payment_method'] ?? 'cash')) ?></span>
                </div>
            </div>

            <div class="text-center border-top">
                <p>Merci de votre visite !<br>A bientôt.</p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}