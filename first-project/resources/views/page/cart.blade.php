@extends('layouts.app')
@section('title', 'mycart')

@section('content')
    <h2 class="mb-4 text-center">keranjang saya</h2>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Kuantitas</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="cartItems">
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Memuat data keranjang...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row justify-content-end mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Harga</h5>
                    <h3 class="text-danger fw-bold" id="cartTotal">Rp 0</h3>
                    <button class="btn btn-primary w-100 mt-3" id="checkoutBtn" disabled onclick="checkout()">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', loadCart)
        async function loadCart() {

            const token = localStorage.getItem('token');
            const cartItems = document.getElementById('cartItems')

            const res = await fetch('http://localhost:8000/api/carts', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })

            const carts = await res.json();
            console.log(carts)

            cartItems.innerHTML = '';
            let total = 0;

            if (carts.length === 0) {
                cartItems.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Keranjang masih kosong
                    </td>
                </tr>
            `;
                document.getElementById('cartTotal').innerText = 'Rp 0';
                return;
            }
            carts.forEach(cart => {
                const price = cart.product.harga
                const subtotal = price * cart.quantity

                total += subtotal;

                cartItems.innerHTML += `
                <tr class="text-center cart-row data-id="${cart.id}" data-price="${price}" data-qty="${cart.quantity}">
                    <td>
                        <input type="checkbox" class="form-check-input cart-check" onchange="updateSelectedTotal()">
                    </td>
                    <td class="text-start">
                        <strong>${cart.product.judul}</strong>
                    </td>
                    <td>Rp ${price}</td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <button class="btn btn-sm btn-outline-secondary"
                            ${cart.quantity <= 1 ? 'disabled' : ''}
                            onclick="updateQty(${cart.id}, ${cart.quantity - 1})">
                            −
                            </button>
                            <span class="fw-semibold">${cart.quantity}</span>
                            <button class="btn btn-sm btn-outline-secondary"
                                onclick="updateQty(${cart.id}, ${cart.quantity + 1})">
                                +
                            </button>
                        </div>
                    </td>
                    <td class="fw-semibold">
                        Rp ${subtotal}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${cart.id})">
                            Hapus
                        </button>
                    </td>
                </tr>
                `;
            })

        }
        async function removeItem(cartId) {
            const token = localStorage.getItem('token');

            if (!confirm('hapus dari keranjang')) return

            await fetch(`http://localhost:8000/api/carts/${cartId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            loadCart()
            updateCartBadge();
        }

        async function updateQty(cartId, qty) {
            const token = localStorage.getItem('token');

            const res = await fetch(`http://localhost:8000/api/carts/${cartId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    quantity: qty
                })
            })

            loadCart()
            updateCartBadge()
            setTimeout(updateSelectedTotal, 200)
        }

        function updateSelectedTotal() {
            let total = 0;
            let selected = 0;

            document.querySelectorAll('.cart-row').forEach(row => {
                const checkbox = row.querySelector('.cart-check');
                if (checkbox.checked) {
                    const price = parseInt(row.dateset.price);
                    const qty = parseInt(row.dateset.qty);
                    total += price * qty;
                    selected++;
                }
            })
            document.getElementById('cartTotal').innerText = 'Rp' + total;
            document.getElementById('checkOutBtn').disabled = selected === 0;
        }
    </script>
