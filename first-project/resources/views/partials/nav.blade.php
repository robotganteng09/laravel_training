<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">TokoKu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li><a class="nav-link active" href="/">Home</a></li>
                <li><a class="nav-link" href="/products">Produk</a></li>
                <li><a class="nav-link" href="/about">Tentang</a></li>
            </ul>
            <ul class="navbar-nav ms-auto" id="rightNav">
                {{-- akan diisi oleh JS --}}
            </ul>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        const token = localStorage.getItem("token");
        const rightNav = document.getElementById("rightNav");

        if (token) {
            try {
                const response = await fetch("http://localhost:8000/api/user", {
                    headers: {
                        "Authorization": `Bearer ${token}`
                    }
                });
                const data = await response.json();
                console.log(data)

                if (response.ok) {
                    rightNav.innerHTML = ` 
                      <li class="nav-item position-relative me-3" id="cartNavItem">
                            <a href="/cart" class="nav-link d-flex align-items-center">
                                <i class="bi bi-cart fs-4 text-dark"></i>
                                <span id="cartCount"
                                    class="badge bg-danger rounded-pill position-absolute p-1"
                                    style="top: 8px; right: -10px; font-size: 0.7rem;">
                                    
                                </span>
                            </a>
                        </li>
                         
                      <li class="nav-item dropdown" id="authNavItem">
                            <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">
                                ${data.user_info.name}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-danger cursor-pointer" id="logoutBtn">Keluar</a></li>
                            </ul>
                        </li>`
                    document.getElementById("logoutBtn").addEventListener("click", async () => {
                        const token = localStorage.getItem("token")

                        if (token) {
                           fetch('http://localhost:8000/api/logout', {
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${token}`
                                }
                            });
                            localStorage.removeItem("token");
                            location.reload();
                        }
                    })
                } else {
                    showGuestNav();
                }
            } catch (error) {
              showGuestNav();
            }
        } else {
            showGuestNav()
        }
        function showGuestNav() {
            rightNav.innerHTML = `
                <li><a class="btn btn-outline-primary me-2" href="/login">Login</a></li>
                <li><a class="btn btn-outline-primary" href="/register">Register</a></li>
            `;
        } 
    })
</script>
