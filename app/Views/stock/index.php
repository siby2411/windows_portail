<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Approvisionnements & Entrées de Stock</h3>
        <p class="text-muted mb-0">Historique des achats auprès des fournisseurs et réapprovisionnements.</p>
    </div>
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addStockModal">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle Entrée de Stock
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Ingrédient</th>
                    <th>Fournisseur</th>
                    <th>Quantité Ajoutée</th>
                    <th>Prix Unitaire</th>
                    <th>Coût Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($entry['entry_date'])) ?></td>
                        <td class="fw-bold"><?= e($entry['ingredient_name']) ?></td>
                        <td><?= e($entry['supplier_name'] ?? 'Fournisseur direct') ?></td>
                        <td><span class="badge bg-success">+ <?= $entry['quantity'] ?></span></td>
                        <td><?= formatPrice((float)$entry['unit_price']) ?></td>
                        <td class="fw-bold"><?= formatPrice((float)$entry['total_price']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($entries)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucun approvisionnement enregistré.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Entrée de Stock -->
<div class="modal fade" id="addStockModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('/stock') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Réapprovisionner le Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Ingrédient</label>
                    <select name="ingredient_id" class="form-select" required>
                        <option value="">-- Choisir un ingrédient --</option>
                        <?php foreach ($ingredients as $ing): ?>
                            <option value="<?= $ing['id'] ?>"><?= e($ing['name']) ?> (<?= e($ing['unit']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fournisseur</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">-- Choisir un fournisseur --</option>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?= $sup['id'] ?>"><?= e($sup['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quantité Reçue</label>
                    <input type="number" step="0.001" name="quantity" class="form-control" required placeholder="ex: 50">
                </div>
                <div class="mb-3">
                    <label class="form-label">Prix d'Achat Unitaire (<?= CURRENCY ?>)</label>
                    <input type="number" step="1" name="unit_price" class="form-control" required placeholder="ex: 500">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger fw-bold">Valider l'Entrée</button>
            </div>
        </form>
    </div>
</div>