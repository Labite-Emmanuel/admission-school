<?php $page = 'register-2'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="container-fuild">
    <div class="login-wrapper w-100 overflow-hidden position-relative flex-wrap d-block vh-100">
        <div class="row">
            <div class="col-lg-6">
                <div class="d-lg-flex align-items-center justify-content-center bg-light-300 d-lg-block d-none flex-wrap vh-100 overflowy-auto bg-01">
                    <div>
                        <img src="{{URL::asset('build/img/authentication/authentication-06.svg')}}" alt="Img">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap ">
                    <div class="col-md-8 mx-auto p-4">
                        <form id="registerForm" action="{{url('register.create')}}" method="POST">
                            @csrf
                            <div>
                                <div class=" mx-auto mb-5 text-center">
                                    <img src="{{URL::asset('logo.jpg')}}"
                                    width="100px" class="img-fluid" alt="Logo">
                                </div>
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class=" mb-4">
                                            <h2 class="mb-2">Register</h2>
                                            <p class="mb-0">Please enter your details to sign up</p>
                                        </div>

                                        {{-- Messages serveur (validation / erreurs contrôleur) --}}
                                        @if (session('success'))
                                            <div class="alert alert-success mb-3">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger mb-3">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        @if ($errors->any())
                                            <div class="alert alert-danger mb-3">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Messages pour la version AJAX (si réactivée plus tard) -->
                                        <div id="error-message" class="alert alert-danger d-none mb-3"></div>
                                        <div id="success-message" class="alert alert-success d-none mb-3"></div>
                                        <div class="mt-4">
                                            <div class="mb-3 ">
                                                <label class="form-label">Name</label>
                                                <div class="input-icon mb-3 position-relative">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-user"></i>
                                                    </span>
                                                    <input type="text" name="name" value="" class="form-control" required>
                                                </div>
                                                <label class="form-label">Email Address</label>
                                                <div class="input-icon mb-3 position-relative">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-mail"></i>
                                                    </span>
                                                    <input type="email" name="email" value="" class="form-control" required>
                                                </div>
                                                <label class="form-label">Username</label>
                                                <div class="input-icon mb-3 position-relative">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-user"></i>
                                                    </span>
                                                    <input type="text" name="username" value="" class="form-control" required>
                                                </div>
                                                <label class="form-label">Password</label>
                                                <div class="pass-group mb-3">
                                                    <input type="password" name="password" class="pass-input form-control" required>
                                                    <span class="ti toggle-password ti-eye-off"></span>
                                                </div>
                                                <label class="form-label">Confirm Password</label>
                                                <div class="pass-group">
                                                    <input type="password" name="password_confirmation" class="pass-input form-control" required>
                                                    <span class="ti toggle-password ti-eye-off"></span>
                                                </div>
                                            </div>
                                            <div class="form-wrap form-wrap-checkbox mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check form-check-md mb-0 me-2">
                                                        <input class="form-check-input mt-0" type="checkbox" id="termsCheckbox" required>
                                                    </div>
                                                    <h6 class="fw-normal text-dark mb-0">I Agree to<a href="#"
                                                            class="hover-a "> Terms & Privacy</a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100" id="registerButton">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="registerSpinner"></span>
                                                <span id="registerButtonText">Sign Up</span>
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <h6 class="fw-normal text-dark mb-0">Already have an account?<a
                                                    href="{{url('login')}}" class="hover-a "> Sign In</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 text-center">
                                    <p class="mb-0 ">Copyright &copy; 2024 - Preskool</p>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>



    </div>
</div>

<script>
    // Gestion de l'activation/désactivation du bouton d'inscription
    document.getElementById('termsCheckbox').addEventListener('change', function() {
        const registerButton = document.getElementById('registerButton');
        registerButton.disabled = !this.checked;
    });

    // Fonction pour gérer l'affichage/masquage du mot de passe
    document.querySelectorAll('.toggle-password').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('ti-eye-off');
                this.classList.add('ti-eye');
            } else {
                input.type = 'password';
                this.classList.remove('ti-eye');
                this.classList.add('ti-eye-off');
            }
        });
    });


    // document.getElementById('registerForm').addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     // Afficher le loader et désactiver le bouton
    //     const registerButton = document.getElementById('registerButton');
    //     const registerSpinner = document.getElementById('registerSpinner');
    //     const registerButtonText = document.getElementById('registerButtonText');
        
    //     registerButton.disabled = true;
    //     registerSpinner.classList.remove('d-none');
    //     registerButtonText.textContent = 'Création du compte...';
        
    //     // Réinitialiser les messages
    //     document.getElementById('error-message').classList.add('d-none');
    //     document.getElementById('success-message').classList.add('d-none');
        
    //     const formData = new FormData(this);
        
    //     fetch(this.action, {
    //         method: 'POST',
    //         body: formData,
    //         headers: {
    //             'X-Requested-With': 'XMLHttpRequest'
    //         }
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.status === 'error') {
    //             const errorDiv = document.getElementById('error-message');
    //             errorDiv.textContent = data.message;
    //             errorDiv.classList.remove('d-none');
                
    //             // Réactiver le bouton
    //             registerButton.disabled = false;
    //             registerSpinner.classList.add('d-none');
    //             registerButtonText.textContent = 'Sign Up';
    //         } else if (data.status === 'success') {
    //             const successDiv = document.getElementById('success-message');
    //             successDiv.textContent = data.message;
    //             successDiv.classList.remove('d-none');
                
    //             // Redirection après un court délai
    //             setTimeout(() => {
    //                 window.location.href = data.redirect;
    //             }, 1000);
    //         }
    //     })
    //     .catch(error => {
    //         const errorDiv = document.getElementById('error-message');
    //         errorDiv.textContent = 'Une erreur est survenue. Veuillez réessayer.';
    //         errorDiv.classList.remove('d-none');
            
    //         // Réactiver le bouton
    //         registerButton.disabled = false;
    //         registerSpinner.classList.add('d-none');
    //         registerButtonText.textContent = 'Sign Up';
    //     });
        
    // });

</script>

@endsection