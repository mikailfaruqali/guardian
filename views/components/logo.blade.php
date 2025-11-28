<div class="logo-container">
    @if (config('snawbar-guardian.logo-path'))
        <img src="{{ asset(config('snawbar-guardian.logo-path')) }}" alt="Logo" class="w-100 h-100">
    @else
        <div class="logo-default">
            @include('snawbar-guardian::components.icons.check-circle')
        </div>
    @endif
</div>
