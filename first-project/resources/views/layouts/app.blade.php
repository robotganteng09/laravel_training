<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'E-Commerce')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    @include('partials.nav')
    <div class="container my-6">

        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            updateCartBadge();
        })
        async function updateCartBadge() {

            const token = localStorage.getItem('token')

            if (!token) return;
            try {
                const response = await fetch("http://localhost:8000/api/carts", {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                })

                const carts = await response.json()
                let totalQty = 0;

                carts.forEach(item => {
                    totalQty += item.quantity
                })

                const badge = document.getElementById("cartCount");
                if (!badge) return;

                if (totalQty === 0) {
                    badge.style.display = "none"
                } else {
                    badge.style.display = "inline-block"
                    badge.textContent = totalQty
                }
            } catch (error) {
                console.error("Gagal update cart badge", error)
            }
        }
    </script>
    @yield('scripts')
</body>

</html>
