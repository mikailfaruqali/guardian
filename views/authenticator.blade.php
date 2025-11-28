@extends('snawbar-guardian::layout')
@section('content')
    @include('snawbar-guardian::components.alerts')
    @include('snawbar-guardian::components.logo')

    @if ($isFirstTime)
        <div class="qr-container">
            <p class="qr-text">
                {{ __('snawbar-guardian::guardian.install-app') }}
            </p>
            <div class="d-flex justify-content-center mb-2">
                {!! $qrCode !!}
            </div>

            @if ($secret)
                <div class="secret-code">
                    {{ $secret }}
                </div>
            @endif

        </div>
    @endif

    <p class="text-description">
        {{ __('snawbar-guardian::guardian.enter-auth-code') }}
    </p>
    <form method="POST" action="{{ route('guardian.authenticator.verify') }}" id="verifyForm">
        @csrf
        @include('snawbar-guardian::components.otp-inputs')
    </form>
    <div class="d-grid gap-3">
        <button type="submit" form="verifyForm" class="btn btn-guardian btn-primary-guardian">
            @include('snawbar-guardian::components.icons.login')

            {{ __('snawbar-guardian::guardian.login') }}
        </button>

        @include('snawbar-guardian::components.logout-button')
    </div>
@endsection
