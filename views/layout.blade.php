<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ session('direction') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @include('snawbar-guardian::components.styles')
</head>

<body dir="{{ session('direction') }}">
    <div class="guardian-container">
        <div class="guardian-card">
            @yield('content')
        </div>
    </div>

    @include('snawbar-guardian::components.scripts')
</body>

</html>
