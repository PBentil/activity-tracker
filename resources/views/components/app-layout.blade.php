<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Activity Tracker') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #ffffff; color: #111827; }

        .nav-bar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-brand {
            font-size: 0.95rem;
            font-weight: 600;
            color: #111827;
            letter-spacing: -0.3px;
        }
        .nav-user {
            font-size: 0.8rem;
            color: #6b7280;
            margin-right: 1rem;
        }
        .nav-logout {
            font-size: 0.8rem;
            color: #6b7280;
            background: none;
            border: 1px solid #e5e7eb;
            padding: 5px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .nav-logout:hover {
            background: #f9fafb;
            color: #111827;
        }
        .page-header {
            border-bottom: 1px solid #f3f4f6;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }
        .page-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
        }
        .page-subtitle {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 2px;
        }
        .btn-primary {
            background: #111827;
            color: #ffffff;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-primary:hover { background: #1f2937; color: #fff; }
        .btn-secondary {
            background: #ffffff;
            color: #374151;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-secondary:hover { background: #f9fafb; color: #111827; }
        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.25rem 1.5rem;
        }
        .stat-number {
            font-size: 1.75rem;
            font-weight: 600;
            color: #111827;
            line-height: 1;
        }
        .stat-label {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 4px;
            font-weight: 400;
        }
        .badge-done {
            background: #f0fdf4;
            color: #15803d;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 500;
            border: 1px solid #bbf7d0;
        }
        .badge-pending {
            background: #fffbeb;
            color: #b45309;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 500;
            border: 1px solid #fde68a;
        }
        .badge-none {
            background: #f9fafb;
            color: #9ca3af;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 500;
            border: 1px solid #e5e7eb;
        }
        .form-input {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 9px 12px;
            font-size: 0.85rem;
            color: #111827;
            outline: none;
            transition: border 0.15s;
            background: #fff;
        }
        .form-input:focus {
            border-color: #111827;
        }
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            padding: 10px 14px;
            border-radius: 6px;
            border: 1px solid #bbf7d0;
            font-size: 0.82rem;
            margin-bottom: 1.25rem;
        }
        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 6px;
            border: 1px solid #fecaca;
            font-size: 0.82rem;
            margin-bottom: 1.25rem;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }
        .data-table th {
            padding: 10px 14px;
            text-align: left;
            font-weight: 500;
            color: #6b7280;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        .data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #f9fafb; }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-box {
            background: white;
            border-radius: 10px;
            width: 100%;
            max-width: 480px;
            margin: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #111827;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #9ca3af;
            cursor: pointer;
            line-height: 1;
        }
        .modal-close:hover { color: #111827; }
        .modal-body { padding: 1.5rem; }
        .section-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
        }
        .handover-section {
            border: 1px solid #fde68a;
            border-radius: 8px;
            background: #fffbeb;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .handover-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 0.25rem;
        }
        .handover-subtitle {
            font-size: 0.75rem;
            color: #b45309;
            margin-bottom: 1rem;
        }
        .tag {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 500;
        }
        a.table-link {
            color: #111827;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.8rem;
        }
        a.table-link:hover { text-decoration: underline; }
        .delete-btn {
            background: none;
            border: none;
            color: #d1d5db;
            font-size: 0.75rem;
            cursor: pointer;
        }
        .delete-btn:hover { color: #ef4444; }

        @media print {
            body * { visibility: hidden; }
            #handoverPrintArea, #handoverPrintArea * { visibility: visible; }
            #handoverPrintArea {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding: 30px;
                background: white;
            }
            #handoverPrintArea .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<nav class="nav-bar">
    <span class="nav-brand">ActivityTracker</span>
    <div style="display: flex; align-items: center;">
        <span class="nav-user">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-logout">Sign out</button>
        </form>
    </div>
</nav>

@isset($header)
    <div class="page-header">
        <div style="max-width: 1100px; margin: 0 auto; padding: 0 2rem;">
            {{ $header }}
        </div>
    </div>
@endisset

<main style="max-width: 1100px; margin: 0 auto; padding: 0 2rem 3rem;">
    {{ $slot }}
</main>
</body>
</html>
