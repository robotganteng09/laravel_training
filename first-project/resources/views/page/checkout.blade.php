@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
    <div class="container my-5">
        <h3 class="mb-4">Rincian Pesanan</h3>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="checkoutItems"></tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5>Total</h5>
                <h4 class="text-danger fw-bold" id="checkoutTotal">Rp 0</h4>
            </div>
            <div class="card-footer bg-white">
                <button class="btn btn-success w-100" onclick="pay()">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', loadCheckout)

        async function loadCheckout() {
            const token = localStorage.getItem('token');
            const cartIds = JSON.parse(localStorage.getItem('checkout_cart_ids'))

            if (!cartIds || cartIds === 0) {
                alert('Checkout tidak valid');
                window.location.href = '/mycart'
                return;
            }
            const res = await fetch('/api/checkout/preview', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cart_ids: cartIds
                })
            });
            const data = await res.json()
            console.log(data)
            if (!res.ok) {
                alert(data.message || 'gagal memuat checkout')
                return
            }

            const tbody = document.getElementById('checkoutItems')
            tbody.innerHTML = ""

            data.items.forEach(item => {
                tbody.innerHTML += `
            <tr>
                <td>${item.product_name}</td>
                <td>${formatRupiah(item.price)}</td>
                <td>${item.qty}</td>
                <td class="text-end">${formatRupiah(item.subtotal)}</td>
            </tr>
            `


            })
            document.getElementById('checkoutTotal').innerText = 'Rp' + formatRupiah(data.total)

        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        async function pay() {
            const token = localStorage.getItem('token')

            const cartIds = JSON.parse(localStorage.getItem('checkout_cart_ids'))
            
            const res = await fetch('/api/checkout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cart_ids: cartIds
                })
            })
            const data = await res.json()
            console.log(data)


            if (!res.ok) {
                alert(data.message || 'checkout gagal')
                return
            }

            snap.pay(data.snap_token, {
                onSuccess: function() {
                    alert('Pembayaran berhasil')
                    localStorage.removeItem('checkout_cart_ids')
                    window.location.href = '/orders'
                },
                onPending: function() {
                    alert('menunggu pembayaran')
                },
                onError: function() {
                    alert('pembayaran gagal')
                }
            })
        }
    </script>
@endsection
