<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Carte & Menu du Restaurant</h3>
        <p class="text-muted mb-0">Gestion des plats, boissons et tarifs du catalogue.</p>
    </div>
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addMenuItemModal">
        <i class="bi bi-plus-lg me-1"></i> Nouveau Plat / Article
    </button>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($menuItems as $item): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0"><?= e($item['name']) ?></h5>
                        <span class="badge bg-danger"><?= formatPrice((float)$item['price']) ?></span>
                    </div>
                    <span class="badge bg-light text-dark mb-2"><?= e($item['category_name']) ?></span>
                    <p class="text-muted small mb-0"><?= e($item['description'] ?? 'Aucune description.') ?></p>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                    <span class="badge <?= $item['is_available'] ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $item['is_available'] ? 'Disponible' : 'Indisponible' ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Ajout Plat -->
<div class="modal fade" id="addMenuItemModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('/menu/new') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Ajouter un Plat au Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Choisir une catégorie --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nom du Plat / Article</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: Poulet Yassa">
                </div>
                <div class="mb-3">
                    <label class="form-label">Prix de Vente (<?= CURRENCY ?>)</label>
                    <input type="number" step="10" name="price" class="form-control" required placeholder="ex: 3500">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description / Ingrédients clés</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Description courte..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger fw-bold">Ajouter au Menu</button>
            </div>
        </form>
    </div>
</div>