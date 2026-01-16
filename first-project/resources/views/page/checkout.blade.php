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
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', loadCheckout)
        
    </script>
@endsection
