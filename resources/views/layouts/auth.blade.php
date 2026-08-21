<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'SMK Mandalahayu 1 Bekasi - LMS')</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&family=Noto+Serif:wght@600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "background": "#fef9f3",
                        "surface": "#fef9f3",
                        "surface-container": "#f2ede7",
                        "surface-container-low": "#f8f3ed",
                        "on-surface": "#1d1b18",
                        "on-surface-variant": "#51443c",
                        "primary": "#50290b",
                        "primary-container": "#6b3f1f",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#eaac84",
                        "secondary": "#835500",
                        "secondary-container": "#feae2c",
                        "on-secondary-container": "#6b4500",
                        "outline": "#84746b",
                        "outline-variant": "#d6c3b8",
                        "error": "#ba1a1a",
                    },
                    fontFamily: {
                        sans: ["Manrope", "ui-sans-serif", "system-ui"],
                        serif: ["Noto Serif", "serif"],
                    }
                }
            }
        }
    </script>
    @include('layouts.partials.ui-system')
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
        .transition-soft { transition: all 0.2s ease; }
        
        /* Sembunyikan icon mata bawaan dari browser (khususnya Edge) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
    <link rel="shortcut icon" href="/favicon.png?v=4" type="image/x-icon">
    <link rel="icon" href="/favicon.png?v=4" type="image/png">
</head>
<body class="bg-surface text-on-surface font-sans antialiased min-h-screen flex items-center justify-center">
    @yield('content')
    @stack('scripts')
</body>
</html>
