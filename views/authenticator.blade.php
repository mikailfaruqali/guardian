@extends('snawbar-guardian::layout')

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm">
                {{ $error }}
            </div>
        @endforeach
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="w-28 h-28 md:w-32 md:h-32 mx-auto mb-3 md:mb-4 rounded-2xl flex items-center justify-center p-3">
        @if (config('snawbar-guardian.logo-path'))
            <img src="{{ asset(config('snawbar-guardian.logo-path')) }}" alt="Logo" class="w-full h-full object-contain">
        @else
            <div class="bg-blue-600 rounded-2xl w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 md:w-18 md:h-18 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        @endif
    </div>

    @if ($isFirstTime)
        <div class="bg-gray-50 border-2 border-dotted border-gray-300 rounded-xl p-3 mb-3">
            <p class="text-gray-600 text-xs mb-2 text-center">
                {{ __('snawbar-guardian::guardian.install-app') }}
            </p>

            <div class="mb-2 flex justify-center">
                {!! $qrCode !!}
            </div>

            @if ($secret)
                <div class="bg-gray-100 p-2 rounded-lg text-xs text-gray-600 font-mono break-all text-center">
                    {{ $secret }}
                </div>
            @endif
        </div>
    @endif

    <p class="text-gray-600 text-sm md:text-base mb-4 md:mb-5 leading-relaxed px-2">
        {{ __('snawbar-guardian::guardian.enter-auth-code') }}
    </p>

    <div class="mb-3 md:mb-4">
        <form method="POST" action="{{ route('guardian.authenticator.verify') }}" id="verifyForm">
            @csrf
            <input type="text" name="code" maxlength="6" placeholder="000000"
                class="w-full h-12 md:h-14 text-center text-lg md:text-xl font-bold tracking-[0.3em] md:tracking-[0.5em] border-2 border-dotted border-gray-300 rounded-2xl bg-gray-50/50 focus:bg-white focus:border-blue-400 focus:outline-none transition-all duration-300"
                autofocus autocomplete="off">
        </form>
    </div>

    <div class="space-y-3">
        <button type="submit" form="verifyForm"
            class="w-full h-11 md:h-12 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
            {{ __('snawbar-guardian::guardian.login') }}
        </button>
    </div>
@endsection
