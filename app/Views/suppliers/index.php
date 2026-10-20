<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gestion des Fournisseurs</h3>
        <p class="text-muted mb-0">Partenaires et prestataires pour l'approvisionnement des cuisines.</p>
    </div>
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
        <i class="bi bi-plus-lg me-1"></i> Nouveau Fournisseur
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Société</th>
                    <th>Contact</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Adresse</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $sup): ?>
                    <tr>
                        <td class="fw-bold"><?= e($sup['company_name']) ?></td>
                        <td><?= e($sup['contact_name']) ?></td>
                        <td><?= e($sup['phone']) ?></td>
                        <td><?= e($sup['email']) ?></td>
                        <td class="text-muted"><?= e($sup['address']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Fournisseur -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('/suppliers') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Ajouter un Fournisseur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nom de la Société</label>
                    <input type="text" name="company_name" class="form-control" required placeholder="Ex: Grand Marché SARL">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nom du Contact</label>
                    <input type="text" name="contact_name" class="form-control" placeholder="Ex: M. Diop">
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control" placeholder="+221 77 ...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="contact@fournisseur.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Dakar, Sénégal"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger fw-bold">Enregistrer</button>
            </div>
        </form>
    </div>
</div>