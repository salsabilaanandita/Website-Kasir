<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — {{ \App\Models\Setting::get('nama_toko', 'Kasir App') }}</title>
    
    <!-- Apple SF Pro / System Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --apple-blue: #0071e3;
            --apple-blue-hover: #0077ed;
            --apple-blue-active: #0062c4;
            --apple-ink: #1d1d1f;
            --apple-muted: #86868b;
            --apple-border: #d2d2d7;
            --apple-border-light: #e5e5ea;
            --apple-bg: #f5f5f7;
            --apple-card: #ffffff;
            --apple-danger: #ff3b30;
            --apple-radius-pill: 9999px;
            --apple-radius-card: 18px;
            --apple-radius-input: 12px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--apple-bg);
            color: var(--apple-ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            margin: auto;
        }

        @stack('styles')
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
