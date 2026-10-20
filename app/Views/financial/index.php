<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Bilan Financier & Dépenses</h3>
        <p class="text-muted mb-0">Suivi des entrées (Chiffre d'affaires) et des sorties (Dépenses d'exploitation).</p>
    </div>
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
        <i class="bi bi-plus-lg me-1"></i> Saisir une Dépense
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white p-3">
            <h6>Chiffre d'Affaires Total</h6>
            <h2 class="fw-bold mb-0"><?= formatPrice($totalRevenue) ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white p-3">
            <h6>Total des Dépenses</h6>
            <h2 class="fw-bold mb-0"><?= formatPrice($totalExpenses) ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark text-white p-3">
            <h6>Bénéfice Net Estimé</h6>
            <h2 class="fw-bold mb-0"><?= formatPrice($totalRevenue - $totalExpenses) ?></h2>
        </div>
    </div>
</div>

<div class="card p-4">
    <h5 class="fw-bold mb-3">Historique des Dépenses</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Intitulé</th>
                    <th>Catégorie</th>
                    <th>Montant</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expenses as $exp): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($exp['expense_date'])) ?></td>
                        <td class="fw-bold"><?= e($exp['title']) ?></td>
                        <td><span class="badge bg-secondary"><?= e($exp['category']) ?></span></td>
                        <td class="text-danger fw-bold"><?= formatPrice((float)$exp['amount']) ?></td>
                        <td class="text-muted small"><?= e($exp['notes']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($expenses)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune dépense enregistrée.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Dépense -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('/financial/expenses') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Enregistrer une Dépense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Intitulé de la dépense</label>
                    <input type="text" name="title" class="form-control" required placeholder="Ex: Facture d'électricité, Achat gaz...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Catégorie</label>
                    <select name="category" class="form-select">
                        <option value="Charges Fixes">Charges Fixes (Loyer, Eau, Électricité)</option>
                        <option value="Approvisionnement">Approvisionnement / Ingrédients</option>
                        <option value="Salaires">Salaires & Personnel</option>
                        <option value="Maintenance">Maintenance & Réparations</option>
                        <option value="Divers">Divers</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Montant (<?= CURRENCY ?>)</label>
                    <input type="number" step="100" name="amount" class="form-control" required placeholder="ex: 25000">
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes / Justificatif</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Référence facture..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger fw-bold">Enregistrer la Dépense</button>
            </div>
        </form>
    </div>
</div>