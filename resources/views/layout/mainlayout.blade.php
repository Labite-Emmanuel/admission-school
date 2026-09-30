<!DOCTYPE html>
@if(!Route::is(['layout-dark']))  
<html lang="en">
@endif
 @if(Route::is(['layout-dark']))   

<html lang="en" data-theme="dark">
 @endif   

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Sholarsapp | School Management System">
    <meta name="keywords" content="admin, estimates, bootstrap, business, html5, responsive, Projects">
    <meta name="author" content="Sholarsapp | School Management System">
    <meta name="robots" content="noindex, nofollow">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <title>Sholarsapp | School Management System</title>

    @include('layout.partials.head')
</head>
@if (
    !Route::is([
        'home',
        'login',
        'login-2',
        'login-3',
        'chat',
        'register',
        'register-2',
        'register-3',
        'email-verification',
        'email-verification-2',
        'email-verification-3',
        'two-step-verification',
        'two-step-verification-2',
        'two-step-verification-3',
        'lock-screen',
        '404-error',
        '500-error',
        'coming-soon',
        'under-maintenance',
        'reset-password',
        'reset-password-2',
        'reset-password-3',
        'reset-password-success',
        'reset-password-success-2',
        'reset-password-success-3',
        'forgot-password',
        'forgot-password-2',
        'forgot-password-3',
        'layout-box',
        'layout-mini',
        'layout-rtl'
    ]))

    <body>
    <!-- @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif -->

@endif

@if (Route::is(['layout-box']))
   <body class="layout-box-mode mini-sidebar">
@endif

@if (Route::is(['layout-mini']))
   <body class="mini-sidebar">
@endif

@if (Route::is(['chat']))

    <body class="main-chat-blk">
@endif
@if (Route::is(['layout-rtl']))
   <body class="layout-mode-rtl">
@endif
@if (Route::is(['lock-screen', '404-error', '500-error','under-maintenance']))

    <body class="error-page">
@endif
@if (Route::is(['coming-soon']))

<body class="bg-light-200">
@endif
@if (Route::is([
        'home',
        'login',
        'login-2',
        'login-3',
        'register',
        'register-2',
        'register-3',
        'email-verification',
        'email-verification-2',
        'email-verification-3',
        'two-step-verification',
        'two-step-verification-2',
        'two-step-verification-3',
        'reset-password',
        'reset-password-2',
        'reset-password-3',
        'reset-password-success',
        'reset-password-success-2',
        'reset-password-success-3',
        'forgot-password',
        'forgot-password-2',
        'forgot-password-3',
    ]))

    <body class="account-page">
@endif

@if (Route::is(['index','teacher-dashboard','student-dashboard','parent-dashboard']))
<div id="global-loader">
    <div class="page-loader"></div>
</div>
@endif
<!-- Main Wrapper -->
<div class="main-wrapper">

    @if (
        !Route::is([
            'home',
            'login',
            'login-2',
            'login-3',
            'register',
            'register-2',
            'register-3',
            'email-verification',
            'email-verification-2',
            'email-verification-3',
            'two-step-verification',
            'two-step-verification-2',
            'two-step-verification-3',
            'lock-screen',
            '404-error',
            '500-error',
            'coming-soon',
            'under-maintenance',
            'reset-password',
            'reset-password-2',
            'reset-password-3',
            'reset-password-success',
            'reset-password-success-2',
            'reset-password-success-3',
            'forgot-password',
            'forgot-password-2',
            'forgot-password-3',
        ]))
        @if (request()->routeIs('home-admission') || request()->routeIs('admission') || request()->is('admission-validate', 'in-process', 'registration-validate', 'items-ordering', 'items-ordering-validate'))
            @include('admissions.layouts.header')
            @include('admissions.layouts.sidebar')
        @else
            @include('layout.partials.header')
            @include('layout.partials.sidebar')
        @endif
    @endif

    @yield('content')
</div>
<!-- /Main Wrapper -->

@include('layout.partials.footer-scripts')
</body>

</html>
