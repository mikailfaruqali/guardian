<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-guardian btn-danger-guardian">
        @include('snawbar-guardian::components.icons.logout')

        {{ __('snawbar-guardian::guardian.logout') }}
    </button>
</form>
