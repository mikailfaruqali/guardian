@if (config('snawbar-guardian.font-path'))
    <style>
        @font-face {
            font-family: 'CustomFont';
            src: url('{{ asset(config('snawbar-guardian.font-path')) }}');
        }

        * {
            font-family: 'CustomFont' !important;
        }
    </style>
@endif

<style>
    body {
        min-height: 100vh;
        background: #f5f7fa;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 0.5rem 1rem;
    }

    .guardian-container {
        max-width: 420px;
        width: 100%;
        padding: 0 0.75rem;
    }

    .guardian-card {
        background: white;
        border: 2px solid #e8eaed;
        border-radius: 1.25rem;
        padding: 1.5rem;
        text-align: center;
        min-height: 300px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .logo-container {
        width: 7.5rem;
        height: 7.5rem;
        margin: -1rem auto 0.125rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
    }

    .logo-default {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1rem;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .logo-default svg {
        width: 4.25rem;
        height: 4.25rem;
        color: white;
    }

    .qr-container {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.875rem;
        padding: 0.875rem;
        margin-bottom: 0.875rem;
    }

    .qr-text {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.625rem;
    }

    .secret-code {
        background: #e9ecef;
        padding: 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        color: #6c757d;
        font-family: monospace;
        word-break: break-all;
    }

    .otp-container {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
        direction: ltr;
    }

    .otp-input {
        width: 2.5rem;
        height: 2.75rem;
        text-align: center;
        font-size: 1.125rem;
        font-weight: 700;
        border: 2px solid #e0e0e0;
        border-radius: 0.625rem;
        background: #fafafa;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        direction: ltr;
    }

    .otp-input:focus {
        background: white;
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }

    .btn-guardian {
        width: 100%;
        height: 2.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        border-radius: 0.75rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-guardian svg {
        width: 1.125rem;
        height: 1.125rem;
    }

    .btn-guardian:active {
        transform: scale(0.97);
    }

    .btn-guardian:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary-guardian {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary-guardian:hover {
        background: linear-gradient(135deg, #5568d3 0%, #63408a 100%);
        color: white;
    }

    .btn-secondary-guardian {
        background: #f1f3f5;
        color: #495057;
        box-shadow: none;
        border: 1px solid #e9ecef;
    }

    .btn-secondary-guardian:hover {
        background: #e9ecef;
        color: #495057;
        border-color: #dee2e6;
    }

    .btn-danger-guardian {
        background: #fff;
        color: #dc3545;
        box-shadow: none;
        border: 1px solid #dc3545;
    }

    .btn-danger-guardian:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .alert-guardian {
        border-radius: 0.75rem;
        font-size: 0.8125rem;
        margin-bottom: 0.875rem;
        border: none;
    }

    .text-description {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
        line-height: 1.5;
        padding: 0 0.5rem;
    }

    @media (min-width: 768px) {
        body {
            padding: 1rem 1rem 1rem;
        }

        .guardian-card {
            padding: 2rem;
        }

        .logo-container {
            width: 8.5rem;
            height: 8.5rem;
            margin: -1.25rem auto 0.125rem;
        }

        .otp-container {
            gap: 0.625rem;
        }

        .otp-input {
            width: 2.75rem;
            height: 3rem;
            font-size: 1.25rem;
        }

        .btn-guardian {
            height: 2.75rem;
        }
    }
</style>
