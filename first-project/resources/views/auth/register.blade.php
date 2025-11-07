@extends('layouts.app')
@section('title', 'register')
@section('content')

    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <h4 class="text-center mb-4">Register Form</h4>
            <form id="registerForm">
                <div class="mb-3">
                    <label for="name" class="form-label">nama</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <div class="invalid-feedback" id="NameError"></div>
                </div>

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

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Password Confirmation</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="Confirm password">
                    <div class="invalid-feedback" id="passwordConfirmationError"></div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <div id="alertBox" class="mt-3 text-center"></div>

            <div class="text-center mt-3">
                <small>Already have an account? <a href="{{ route('login') }}">Login here</a></small>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const API_REGISTER = "http://localhost:8000/api/register";

        const form = document.getElementById('registerForm')

        const alertBox = document.getElementById('alertBox')

        const nameInput = document.getElementById('name')
        const EmailInput = document.getElementById('email')
        const PassInput = document.getElementById('password')
        const PassConfirm = document.getElementById("password_confirmation")
        const NameError = document.getElementById("nameError")
        const EmailError = document.getElementById("emailError")
        const PasswordError = document.getElementById("passwordError")
        const PassCError = document.getElementById("passwordConfirmationError")

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            [nameInput, EmailInput, PassInput, PassConfirm].forEach(input => input.classList.remove(
                'is-invalid'));

            [NameError, EmailError, PasswordError, PassCError].forEach(error => error.textContent = '');

            alertBox.innerHTML = '<div class="text-secondary">Processing...</div>';

            try {
                const response = await fetch(API_REGISTER, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringfy({
                        name: nameInput.value,
                        email: EmailInput.value,
                        password: PassInput.value,
                        password_confirmation: PassConfirm.value
                    })
                });
                const data = await response.json()
                if (response.ok) {
                    alertBox.innerHTML = `
                <div class="alert alert-success" role="alert">
                    Register berhasil! Silakan login...
                </div>
            `;
                    form.reset();
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}"
                    }, 1500);
                } else {
                    if (data.errors) {
                        if (data.errors.name) {
                            nameInput.classList.add('is-invalid')
                            NameError.textContent = data.errors.name[0];
                        }
                        if (data.errors.email) {
                            EmailInput.classList.add('is-invalid')
                            EmailError.textContent = data.errors.email[0];
                        }
                        if (data.errors.password) {
                            PassInput.classList.add('is-invalid')
                            PassInput.textContent = data.errors.password[0];
                        }
                        if (data.errors.password_confirmation) {
                            password_confirmation.classList.add('is-invalid')
                            PassCError.textContent = data.errors.pas[0];
                        }
                    } else {
                        alertBox.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        ${data.message || 'Register gagal. Silakan periksa kembali data Anda.'}
                    </div>
                `;
                    }
                }
            } catch (e) {
                console.error('Register Error:', e)

                alertBox.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    Terjadi kesalahan server. Silakan coba lagi nanti.
                </div>
            `;
            }
        })
    </script>
@endsection
