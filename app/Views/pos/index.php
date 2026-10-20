<div class="row g-3">
    <!-- Panneau de Gauche : Catalogue Produits & Filtres -->
    <div class="col-md-7">
        <div class="card p-3 mb-3">
            <div class="d-flex gap-2 overflow-auto pb-2">
                <button class="btn btn-dark active">Tous</button>
                <?php foreach ($categories as$cat): ?>
                    <button class="btn btn-outline-secondary"><?= e($cat['name']) ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 g-3">
            <?php foreach ($menuItems as$item): ?>
                <div class="col">
                    <div class="card h-100 text-center p-2 cursor-pointer shadow-sm hover-top" onclick="addToCart(<?= $item['id'] ?>, '<?= e($item['name']) ?>', <?=$item['price'] ?>)">
                        <div class="card-body p-2">
                            <i class="bi bi-egg-fried fs-1 text-danger"></i>
                            <h6 class="fw-bold mb-1 mt-2"><?= e($item['name']) ?></h6>
                            <span class="badge bg-danger fs-6"><?= formatPrice((float)$item['price']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Panneau de Droite : Ticket Caisse & Encaissement -->
    <div class="col-md-5">
        <div class="card p-3">
            <h5 class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                <span>Commande POS</span>
                <span class="badge bg-success">Caisse Ouverte</span>
            </h5>

            <div class="mb-3">
                <select id="table_id" class="form-select">
                    <option value="">-- Sélectionner une Table (Sur place) --</option>
                    <?php foreach ($tables as$t): ?>
                        <option value="<?= $t['id'] ?>"><?= e($t['table_number']) ?> (cap. <?=$t['capacity'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="table-responsive" style="max-height: 300px;">
                <table class="table table-sm align-middle" id="cart-table">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th style="width: 80px;">Qté</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">
                        <!-- Rempli en JS -->
                    </tbody>
                </table>
            </div>

            <div class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between fs-4 fw-bold mb-3">
                    <span>TOTAL :</span>
                    <span id="cart-total" class="text-danger">0 FCFA</span>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <button class="btn btn-outline-primary w-100 py-2 fw-bold" onclick="processOrder('cash')"><i class="bi bi-cash me-1"></i> Espèces</button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-success w-100 py-2 fw-bold" onclick="processOrder('card')"><i class="bi bi-credit-card me-1"></i> Carte / Mobile</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

function addToCart(id, name, price) {
    let item = cart.find(i => i.id === id);
    if (item) {
        item.quantity++;
    } else {
        cart.push({ id, name, price, quantity: 1 });
    }
    renderCart();
}

function renderCart() {
    let body = document.getElementById('cart-body');
    body.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;
        body.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>
                    <input type="number" min="1" class="form-control form-control-sm text-center" value="${item.quantity}" onchange="updateQty(${index}, this.value)">
                </td>
                <td class="fw-bold">${itemTotal} FCFA</td>
                <td><button class="btn btn-sm btn-link text-danger" onclick="removeItem(${index})"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
    });

    document.getElementById('cart-total').innerText = total.toLocaleString() + ' FCFA';
}

function updateQty(index, qty) {
    cart[index].quantity = parseInt(qty);
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

function processOrder(paymentMethod) {
    if (cart.length === 0) { alert('Panier vide !'); return; }

    let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let tableId = document.getElementById('table_id').value;

    fetch('<?= url('/pos/order/store') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            items: cart,
            total: total,
            payment_method: paymentMethod,
            table_id: tableId || null
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Commande validée ! N° ' + data.order_number);
            window.open('<?= url('/orders/') ?>' + data.order_id + '/ticket', '_blank');
            cart = [];
            renderCart();
        } else {
            alert(data.message);
        }
    });
}
</script>