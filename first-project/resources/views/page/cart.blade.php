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
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded',loadCart)
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
                const Subtotal = price * cart.quantity
               
                total += Subtotal;
            })
        }
    </script>
