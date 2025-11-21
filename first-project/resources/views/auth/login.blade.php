@extends('layouts.app')
@section('title', 'login')

@section('content')
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <h4 class="text-center mb-4">Login Form</h4>
            <form id="loginForm">


                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="invalid-feedback" id="emailError"></div>
                </div>


                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="invalid-feedback" id="passwordError"></div>
                </div>


                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <div id="alertBox" class="mt-3 text-center"></div>

            <div class="text-center mt-3">
                <small>Dont have an account? <a href="{{ route('register') }}">Register here</a></small>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const API_LOGIN = "http://127.0.0.1:8000/api/login"

        const form = document.getElementById('loginForm')

        const alertBox = document.getElementById('alertBox')
        const EmailInput = document.getElementById('email')
        const PassInput = document.getElementById('password')
        const EmailError = document.getElementById("emailError")
        const PasswordError = document.getElementById("passwordError")

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            [EmailInput, PassInput].forEach(input => input.classList.remove('is-invalid'));
            EmailError.textContent = ""
            PasswordError.textContent = ""
            alertBox.innerHTML = ""

            try {
                const response = await fetch(API_LOGIN, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: EmailInput.value,
                        password: PassInput.value

                    })
                    // console.log($email);
                    // console.log($password);
                })
                const data = await response.json();

                if (response.ok && data.token) {
                    localStorage.setItem('token', data.token)
                    alertBox.innerHTML = `
                <div class="alert alert-success" role="alert">
                    Login successful! Redirecting...
                </div>
            `;

                    setTimeout(() => {
                        window.location.href = "{{ url('/') }}"
                    }, 1000);
                } else {
                    if (data.errors) {
                        if (data.errors.email) {
                            EmailInput.classList.add('is-invalid')
                            EmailError.textContent = data.errors.email[0]
                        }
                        if (data.errors.password) {
                            PassInput.classList.add('is-invalid')
                            password.textContent = data.errors.password[0]
                        }
                    } else {
                        alertBox.innerHTML = `
                            <div class="alert alert-success" role="alert">
                                Login successful! Redirecting...
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Login Error:', error);
                alertBox.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Server error occurred. Please try again later.
                    </div>
                `;
            }
        })
    </script>
@endsection
