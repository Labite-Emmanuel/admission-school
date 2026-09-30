<?php $page = 'forgot-password'; ?>
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
                <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap">
                    <div class="col-md-8 mx-auto p-4">
                        <form action="{{ route('forgot-password.update') }}" method="post">
                            @csrf
                            <div>
                                <div class="mx-auto mb-5 text-center">
                                    <img src="{{URL::asset('logo.jpg')}}" width="100px" class="img-fluid" alt="Logo">
                                </div>
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <h2 class="mb-2">Reset Password</h2>
                                            <p class="mb-0">Enter your email and choose a new password</p>
                                        </div>

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

                                        <div class="mb-3">
                                            <label class="form-label">Email :</label>
                                            <div class="input-icon mb-3 position-relative">
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-mail"></i>
                                                </span>
                                                <input type="email"
                                                       name="email"
                                                       value="{{ old('email') }}"
                                                       class="form-control"
                                                       placeholder="Enter your email">
                                            </div>

                                            <label class="form-label">New Password :</label>
                                            <div class="pass-group mb-3">
                                                <input type="password"
                                                       name="password"
                                                       placeholder="Enter your new password"
                                                       class="pass-input form-control">
                                                <span class="ti toggle-password ti-eye-off"></span>
                                            </div>

                                            <label class="form-label">Confirm Password :</label>
                                            <div class="pass-group">
                                                <input type="password"
                                                       name="password_confirmation"
                                                       placeholder="Confirm your new password"
                                                       class="pass-input form-control">
                                                <span class="ti toggle-password ti-eye-off"></span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100">
                                                Update Password
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <h6 class="fw-normal text-dark mb-0">
                                                Remember your password?
                                                <a href="{{ route('login') }}" class="hover-a"> Sign In</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 text-center">
                                    <p class="mb-0">Copyright &copy; 2024 - IESA</p>
                                </div>
                            </div>
                        </form>

                        <script>
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
