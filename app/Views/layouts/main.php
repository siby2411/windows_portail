<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Omega Restaurant') ?></title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 250px; --primary-color: #d90429; --dark-bg: #111827; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', system-ui, sans-serif; }
        .sidebar { width: var(--sidebar-width); position: fixed; top: 0; bottom: 0; left: 0; background: var(--dark-bg); color: #fff; z-index: 1000; }
        .sidebar .nav-link { color: #9ca3af; padding: 12px 20px; font-weight: 500; border-radius: 8px; margin: 4px 12px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: var(--primary-color); color: #fff; }
        .main-content { margin-left: var(--sidebar-width); padding: 30px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column justify-content-between p-3">
        <div>
            <div class="d-flex align-items-center gap-2 px-3 py-3 mb-3 border-bottom border-secondary">
                <i class="bi bi-cup-hot-fill fs-3 text-danger"></i>
                <span class="fs-5 fw-bold tracking-wide">OMEGA POS</span>
            </div>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item"><a href="<?= url('/dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Tableau de bord</a></li>
                <li class="nav-item"><a href="<?= url('/pos') ?>" class="nav-link bg-danger text-white"><i class="bi bi-display me-2"></i> Terminal Caisse (POS)</a></li>
                <li class="nav-item"><a href="<?= url('/orders') ?>" class="nav-link"><i class="bi bi-receipt me-2"></i> Commandes & Cuisine</a></li>
                <li class="nav-item"><a href="<?= url('/reservations') ?>" class="nav-link"><i class="bi bi-calendar-check me-2"></i> Réservations</a></li>
                <li class="nav-item"><a href="<?= url('/menu') ?>" class="nav-link"><i class="bi bi-journal-richtext me-2"></i> Carte & Menu</a></li>
                <li class="nav-item"><a href="<?= url('/ingredients') ?>" class="nav-link"><i class="bi bi-box-seam me-2"></i> Ingrédients & Fiches</a></li>
                <li class="nav-item"><a href="<?= url('/stock') ?>" class="nav-link"><i class="bi bi-truck me-2"></i> Approvisionnement</a></li>
                <li class="nav-item"><a href="<?= url('/customers') ?>" class="nav-link"><i class="bi bi-people me-2"></i> Clients</a></li>
                <li class="nav-item"><a href="<?= url('/financial') ?>" class="nav-link"><i class="bi bi-graph-up-arrow me-2"></i> Finances & Dépenses</a></li>
            </ul>
        </div>
        <div class="px-3 py-2 border-top border-secondary text-muted small">
            Connecté : <strong><?= e($_SESSION['user_name'] ?? 'Caissier') ?></strong>
        </div>
    </div>

    <div class="main-content">
        <?= $content ?>
    </div>




<!-- ✅ Correct -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   
</body>
</html>