@extends('layouts.app')
@section('title', 'home')

@section('content')
    <h2 class="mb-4 text-center">Daftar Produk</h2>
    <div id="productList" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

    </div>
@endsection
@section('scripts')
    <script>
        const API_URL = "http://127.0.0.1:8000/api/products";

        const token = localStorage.getItem("token");
        const IS_LOGGED_IN = token ? true : false;
        async function fetchProducts() {
            const container = document.getElementById('productList')
            container.innerHTML = '<p class="text-center text-muted">Memuat produk...</p>';
            try {
                const res = await fetch(API_URL);
                const json = await res.json();
                const products = json.data;
                console.log(products)
                container.innerHTML = '';
                if (!products || products.length === 0) {
                    container.innerHTML = '<p class="text-center text-muted">Belum ada produk tersedia.</p>';
                    return
                }
                products.forEach(product => {
                    const col = document.createElement('div')
                    col.innerHTML = `
                    <div class="card shadow-sm h-100">
                        <img src=${product.gambar} class="card-img-top" alt="Nama Produk">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${product.judul}</h5>
                            <h6 class="card-text">${product.harga}</h6>
                            <p class="card-text">${product.deskripsi}</p>
                           <a class="btn btn-primary mt-auto add-to-cart"data-id="${product.id}"> Masukkan ke keranjang </a>
                        </div>
                    </div>
                    `
                    container.appendChild(col);
                })
            } catch (error) {
                container.innerHTML = `<p class="text-danger text-center">Gagal memuat data produk.</p>`;
                console.error(error);
            }
        }
        fetchProducts()
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-to-cart')) {
                e.preventDefault();

                if (!IS_LOGGED_IN) {
                    window.location.href = '/login';
                    return
                }

                const productId = e.target.getAttribute('data-id')
                addToCart(productId)
            }
        });

        async function addToCart(productId) {
            const token = localStorage.getItem('token')

            const response = await fetch("http://localhost:8000/api/carts", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            });
            const data = await response.json()
            if (!response.ok) {
                alert(data.error || "gagal menambah cart")
                return;
            }
            updateCartBadge()
            alert("Berhasil menambah keranjang")
        }
    </script>

@endsection
