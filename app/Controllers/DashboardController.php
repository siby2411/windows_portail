<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class DashboardController
{
    public function index(): string
    {
        $todayRevenue = Database::fetch("SELECT SUM(total) as total FROM orders WHERE DATE(created_at) = CURDATE() AND status = 'paid'")['total'] ?? 0;
        $todayOrders = Database::fetch("SELECT COUNT(*) as count FROM orders WHERE DATE(created_at) = CURDATE()")['count'] ?? 0;
        $lowStockIngredients = Database::fetchAll("SELECT * FROM ingredients WHERE stock_qty <= alert_qty");

        extract([
            'todayRevenue' => (float)$todayRevenue,
            'todayOrders' => (int)$todayOrders,
            'lowStockIngredients' => $lowStockIngredients,
            'title' => 'Tableau de Bord - Restaurant'
        ]);

        ob_start();
        ?>
        <h3 class="fw-bold mb-4">Tableau de bord</h3>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-danger text-white p-3">
                    <h6>Ventes du Jour</h6>
                    <h2 class="fw-bold mb-0"><?= formatPrice($todayRevenue) ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white p-3">
                    <h6>Commandes du Jour</h6>
                    <h2 class="fw-bold mb-0"><?= $todayOrders ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark p-3">
                    <h6>Alertes Stock Ingrédients</h6>
                    <h2 class="fw-bold mb-0"><?= count($lowStockIngredients) ?></h2>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <h5 class="fw-bold mb-3">Ingrédients en Seuil d'Alerte</h5>
            <table class="table align-middle">
                <thead>
                    <tr><th>Ingrédient</th><th>Stock Actuel</th><th>Seuil Alerte</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($lowStockIngredients as $ing): ?>
                        <tr>
                            <td><?= e($ing['name']) ?></td>
                            <td><span class="badge bg-danger"><?= $ing['stock_qty'] ?> <?= e($ing['unit']) ?></span></td>
                            <td><?= $ing['alert_qty'] ?> <?= e($ing['unit']) ?></td>
                            <td><a href="<?= url('/stock') ?>" class="btn btn-sm btn-outline-dark">Approvisionner</a></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($lowStockIngredients)): ?>
                        <tr><td colspan="4" class="text-center text-muted">Tous les stocks sont normaux.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        $content = ob_get_clean();

        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        return ob_get_clean();
    }
}