<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Activity Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --emerald: #10b981;
            --emerald-dark: #059669;
            --emerald-light: #d1fae5;
            --slate: #1e293b;
            --slate-light: #f8fafc;
        }
        body { background-color: #f0fdf4; }
        .nav-bar {
            background: #1e293b;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        }
        .nav-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #10b981;
            letter-spacing: 0.5px;
        }
        .nav-user {
            color: #94a3b8;
            font-size: 0.875rem;
        }
        .nav-logout {
            background: #10b981;
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .nav-logout:hover { background: #059669; }
        .page-header {
            background: white;
            border-bottom: 2px solid #10b981;
            padding: 1rem 0;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: #10b981;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary:hover { background: #059669; color: white; }
        .btn-secondary {
            background: #1e293b;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover { background: #0f172a; color: white; }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.07);
            padding: 1.5rem;
            border-left: 4px solid #10b981;
        }
        .badge-done {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-none {
            background: #f1f5f9;
            color: #64748b;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .form-input {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: border 0.2s;
            outline: none;
        }
        .form-input:focus { border-color: #10b981; box-shadow: 0 0 0 3px #d1fae5; }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            margin-bottom: 1rem;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="font-sans antialiased">

<nav class="nav-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="nav-brand">ActivityTracker</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="nav-user"> {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-logout">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

@isset($header)
    <div class="page-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </div>
@endisset

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    {{ $slot }}
</main>

</body>
</html>
