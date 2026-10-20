<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gestion des Clients & Fidélité</h3>
        <p class="text-muted mb-0">Base de données des clients réguliers et points de fidélité cumulés.</p>
    </div>
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
        <i class="bi bi-plus-lg me-1"></i> Nouveau Client
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nom du Client</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Points de Fidélité</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td class="fw-bold"><?= e($c['name']) ?></td>
                        <td><?= e($c['phone']) ?></td>
                        <td><?= e($c['email']) ?></td>
                        <td><span class="badge bg-warning text-dark fw-bold"><?= $c['loyalty_points'] ?> pts</span></td>
                        <td class="text-muted"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Client -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('/customers') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Enregistrer un Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: Aminata Fall">
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control" placeholder="+221 77 ...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="aminata@example.com">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger fw-bold">Enregistrer</button>
            </div>
        </form>
    </div>
</div>