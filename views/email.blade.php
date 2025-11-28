@extends('snawbar-guardian::layout')
@section('content')
    @include('snawbar-guardian::components.alerts')
    @include('snawbar-guardian::components.logo')

    <p class="text-description">
        {{ __('snawbar-guardian::guardian.enter-email-code') }}
    </p>
    <form method="POST" action="{{ route('guardian.email.verify') }}" id="verifyForm">
        @csrf
        @include('snawbar-guardian::components.otp-inputs')
    </form>
    <div class="d-grid gap-3">
        <button type="submit" form="verifyForm" class="btn btn-guardian btn-primary-guardian">
            @include('snawbar-guardian::components.icons.login')

            {{ __('snawbar-guardian::guardian.login') }}
        </button>
        <form method="POST" action="{{ route('guardian.email.send') }}">
            @csrf
            <button type="submit" class="btn btn-guardian btn-secondary-guardian">
                @include('snawbar-guardian::components.icons.resend')

                {{ __('snawbar-guardian::guardian.resend') }}
            </button>
        </form>

        @include('snawbar-guardian::components.logout-button')
    </div>
@endsection
