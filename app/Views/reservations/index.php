<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Gestion des Réservations</h3>
        <p class="text-muted mb-0">Planification et suivi des tables et des convives.</p>
    </div>
    <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#addReservationModal">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle Réservation
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Table</th>
                    <th>Date & Heure</th>
                    <th>Convives</th>
                    <th>Statut</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                    <tr>
                        <td class="fw-bold"><?= e($res['customer_name']) ?></td>
                        <td><span class="badge bg-secondary"><?= e($res['table_number'] ?? 'Non assignée') ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($res['reservation_time'])) ?></td>
                        <td><span class="badge bg-dark"><?= $res['guests_count'] ?> pers.</span></td>
                        <td>
                            <span class="badge bg-success"><?= ucfirst($res['status']) ?></span>
                        </td>
                        <td class="text-muted small"><?= e($res['notes']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($reservations)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Aucune réservation enregistrée.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout Réservation -->
<div class="modal fade" id="addReservationModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('/reservations') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Nouvelle Réservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Client</label>
                    <select name="customer_id" class="form-select" required>
                        <option value="">-- Choisir un client --</option>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= e($c['name']) ?> (<?= e($c['phone']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Table Souhaitée</label>
                    <select name="table_id" class="form-select">
                        <option value="">-- Assigner une table (optionnel) --</option>
                        <?php foreach ($tables as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= e($t['table_number']) ?> (Capacité: <?= $t['capacity'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date & Heure</label>
                    <input type="datetime-local" name="reservation_time" class="form-control" required value="<?= date('Y-m-d\TH:i') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de Convives</label>
                    <input type="number" name="guests_count" class="form-control" min="1" value="2" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes particulières</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Anniversaire, allergie..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger fw-bold">Enregistrer</button>
            </div>
        </form>
    </div>
</div>