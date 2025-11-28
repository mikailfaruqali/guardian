@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-guardian">
            {{ $error }}
        </div>
    @endforeach
@endif

@if (session('success'))
    <div class="alert alert-success alert-guardian">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-guardian">
        {{ session('error') }}
    </div>
@endif
