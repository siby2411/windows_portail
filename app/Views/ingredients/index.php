<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Ingrédients & Fiches Techniques</h3>
        <p class="text-muted mb-0">Gestion du stock brut et des seuils d'alerte pour le déstockage automatique.</p>
    </div>
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addIngredientModal">
        <i class="bi bi-plus-lg me-1"></i> Nouvel Ingrédient
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Ingrédient</th>
                    <th>Unité</th>
                    <th>Stock Actuel</th>
                    <th>Seuil d'Alerte</th>
                    <th>Coût Unitaire Estimé</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ingredients as $ing): 
                    $isAlert = $ing['stock_qty'] <= $ing['alert_qty'];
                ?>
                    <tr>
                        <td class="fw-bold"><?= e($ing['name']) ?></td>
                        <td><span class="badge bg-light text-dark"><?= e($ing['unit']) ?></span></td>
                        <td class="fw-bold <?= $isAlert ? 'text-danger' : 'text-success' ?>"><?= $ing['stock_qty'] ?> <?= e($ing['unit']) ?></td>
                        <td><?= $ing['alert_qty'] ?> <?= e($ing['unit']) ?></td>
                        <td><?= formatPrice((float)$ing['unit_cost']) ?></td>
                        <td>
                            <?php if ($isAlert): ?>
                                <span class="badge bg-danger">Stock Critique</span>
                            <?php else: ?>
                                <span class="badge bg-success">Normal</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ingrédient -->
<div class="modal fade" id="addIngredientModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('/ingredients') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Ajouter un Ingrédient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nom de l'ingrédient</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: Riz Brisé, Poulet, Oignon...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Unité de mesure</label>
                    <select name="unit" class="form-select">
                        <option value="kg">Kilogramme (kg)</option>
                        <option value="g">Gramme (g)</option>
                        <option value="l">Litre (l)</option>
                        <option value="cl">Centilitre (cl)</option>
                        <option value="piece">Pièce</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Seuil d'alerte</label>
                    <input type="number" step="0.001" name="alert_qty" class="form-control" value="5.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Coût unitaire d'achat (<?= CURRENCY ?>)</label>
                    <input type="number" step="1" name="unit_cost" class="form-control" value="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger fw-bold">Enregistrer</button>
            </div>
        </form>
    </div>
</div>