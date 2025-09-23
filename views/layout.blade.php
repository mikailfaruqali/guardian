<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ session('direction') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

    @if (config('snawbar-guardian.font-path'))
        <style>
            @font-face {
                font-family: 'CustomFont';
                src: url('{{ config('snawbar-guardian.font-path') }}') format('woff2'),
                    url('{{ config('snawbar-guardian.font-path') }}') format('woff'),
                    url('{{ config('snawbar-guardian.font-path') }}') format('truetype');
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }

            * {
                font-family: 'CustomFont', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            }
        </style>
    @endif
</head>

<body
    class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 flex items-center justify-center px-2 pt-12 pb-16 md:px-6 md:pt-4 md:pb-20"
    dir="{{ session('direction') }}">
    <div class="w-full max-w-md px-1 md:px-4">
        <div
            class="bg-white/90 backdrop-blur-sm border-2 border-dotted border-gray-300 rounded-3xl p-5 md:p-8 text-center min-h-[350px] flex flex-col justify-center shadow-sm">
            @yield('content')
        </div>
    </div>
</body>

</html>
