<?php $page = 'login-2'; ?>
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
                        <form id="loginForm" action="{{url('signin')}}" method="post">
                            @csrf
                            <div>
                                <div class=" mx-auto mb-5 text-center">
                                    <img src="{{URL::asset('logo.jpg')}}"
                                        width="100px" class="img-fluid" alt="Logo">
                                </div>
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class=" mb-4">
                                            <h2 class="mb-2">Welcome</h2>
                                            <p class="mb-0">Please enter your details to sign in</p>
                                        </div>
                                        {{-- Messages serveur (validation + erreurs contrôleur) --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger mb-3">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger mb-3">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        @if (session('success'))
                                            <div class="alert alert-success mb-3">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                    
                                        <div class="mb-3 ">
                                            <label class="form-label">Email or Username :</label>
                                            <div class="input-icon mb-3 position-relative">
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                                <input type="text"
                                                       name="email"
                                                       value="{{ old('email') }}"
                                                       class="form-control"
                                                       placeholder="Enter your email or username">
                                            </div>
                                            <label class="form-label">Password :</label>
                                            <div class="pass-group">
                                                <input type="password" name="password" placeholder="Enter your password" class="pass-input form-control">
                                                <span class="ti toggle-password ti-eye-off"></span>
                                            </div>
                                        </div>
    
                                        <div class="form-wrap form-wrap-checkbox mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-check-md mb-0">
                                                    <input class="form-check-input mt-0" type="checkbox">
                                                </div>
                                                <p class="ms-1 mb-0 ">Remember Me</p>
                                            </div>
                                            <div class="text-end ">
                                                <a href="{{url('forgot-password')}}" class="link-danger">Forgot
                                                    Password?</a>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100" id="loginButton">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="loginSpinner"></span>
                                                <span id="loginButtonText">Sign In</span>
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <h6 class="fw-normal text-dark mb-0">Don't have an account? <a
                                                    href="{{url('register')}}" class="hover-a "> Create Account</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 text-center">
                                    <p class="mb-0 ">Copyright &copy; 2024 - Preskool</p>
                                </div>
                            </div>
                        </form>

                        <script>
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
                        </script>
                    </div>

                </div>
            </div>
        </div>



    </div>
</div>

@endsection