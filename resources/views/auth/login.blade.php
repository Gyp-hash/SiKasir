@extends('layouts.app', ['title' => 'Masuk - Sikasir Angkringan'])

@push('styles')
<style>
/* =========================================================
   LOGIN — Premium Dark Theme, Single Centered Card
   ========================================================= */

body, html { margin: 0; padding: 0; }

.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;

    /* Dark layered background */
    background: #002529;
    padding: 1.5rem 1rem;
}

/* ── Background decorative elements ──────────────────────── */

/* Large soft glow top-right */
.login-page::before {
    content: '';
    position: fixed;
    top: -180px;
    right: -180px;
    width: 520px;
    height: 520px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(15,164,175,0.18) 0%, transparent 70%);
    pointer-events: none;
}

/* Soft glow bottom-left */
.login-page::after {
    content: '';
    position: fixed;
    bottom: -160px;
    left: -140px;
    width: 440px;
    height: 440px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(2,73,80,0.25) 0%, transparent 70%);
    pointer-events: none;
}

/* Subtle dot-grid texture */
.login-bg-grid {
    position: fixed;
    inset: 0;
    background-image: radial-gradient(rgba(175,221,229,0.07) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
}

/* ── Card ──────────────────────────────────────────────────── */
.login-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 400px;
    background: #ffffff;
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow:
        0 0 0 1px rgba(15,164,175,0.12),
        0 8px 32px rgba(0,0,0,0.35),
        0 32px 80px rgba(0,0,0,0.25);
}

/* ── Logo block (dark header inside card) ─────────────────── */
.login-card-header {
    background: linear-gradient(160deg, #003135 0%, #024950 70%, #035a64 100%);
    padding: 2.5rem 2.25rem 2rem;
    position: relative;
    overflow: hidden;
    text-align: center;
}

/* Decorative circle in header */
.login-card-header::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(15,164,175,0.12);
    pointer-events: none;
}
.login-card-header::after {
    content: '';
    position: absolute;
    bottom: -40px;
    left: -40px;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: rgba(175,221,229,0.06);
    pointer-events: none;
}

.login-logo-wrap {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 68px;
    height: 68px;
    border-radius: 20px;
    background: rgba(255,255,255,0.08);
    border: 1.5px solid rgba(255,255,255,0.12);
    margin-bottom: 1.25rem;
    backdrop-filter: blur(8px);
}

.login-app-name {
    position: relative;
    z-index: 1;
    font-size: 1.4rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin-bottom: 0.3rem;
}

.login-app-tagline {
    position: relative;
    z-index: 1;
    font-size: 0.78rem;
    color: rgba(175,221,229,0.7);
    font-weight: 400;
    letter-spacing: 0.02em;
}

/* ── Form section ─────────────────────────────────────────── */
.login-form-body {
    padding: 2rem 2.25rem 1.5rem;
    background: #ffffff;
}

.login-form-heading {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #94A3B8;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.login-form-heading::before,
.login-form-heading::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #F1F5F9;
}

/* Labels */
.login-form-body .form-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.4rem;
    letter-spacing: 0.01em;
    display: block;
}

/* Input fields */
.login-input {
    width: 100%;
    border: 1.5px solid #E2E8F0;
    border-radius: 0.625rem;
    padding: 0.7rem 0.875rem;
    font-size: 0.875rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #0F172A;
    background: #F8FAFC;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    box-sizing: border-box;
    -webkit-appearance: none;
}
.login-input:focus {
    border-color: #0FA4AF;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(15,164,175,0.12);
}
.login-input::placeholder { color: #CBD5E1; }

/* Prevent autofill yellow flash */
.login-input:-webkit-autofill,
.login-input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 40px #F8FAFC inset !important;
    -webkit-text-fill-color: #0F172A !important;
}

/* Error alert */
.login-error {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #FFF5F3;
    border: 1px solid #FED7D2;
    border-radius: 0.625rem;
    padding: 0.625rem 0.875rem;
    margin-bottom: 1.25rem;
    color: #C53030;
    font-size: 0.82rem;
    font-weight: 500;
}

/* Submit button */
.btn-login {
    display: block;
    width: 100%;
    background: linear-gradient(135deg, #003135 0%, #0c8c96 60%, #0FA4AF 100%);
    border: none;
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.01em;
    padding: 0.8rem 1rem;
    border-radius: 0.625rem;
    cursor: pointer;
    margin-top: 1.25rem;
    position: relative;
    overflow: hidden;
    transition: transform 0.12s ease, box-shadow 0.12s ease;
    box-shadow: 0 4px 12px rgba(15,164,175,0.25);
}
.btn-login::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0);
    transition: background 0.2s ease;
}
.btn-login:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(15,164,175,0.35);
}
.btn-login:hover::before { background: rgba(255,255,255,0.08); }
.btn-login:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(15,164,175,0.2);
}

/* ── Card footer ──────────────────────────────────────────── */
.login-card-footer {
    text-align: center;
    padding: 0.875rem 2.25rem 1.25rem;
    font-size: 0.7rem;
    color: #94A3B8;
    background: #FAFBFD;
    border-top: 1px solid #F1F5F9;
}
</style>
@endpush

@section('content')
<div class="login-page">

    {{-- Background texture --}}
    <div class="login-bg-grid"></div>

    <div class="login-card">

        {{-- Header block dengan dark background --}}
        <div class="login-card-header">
            <div class="login-logo-wrap">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    {{-- Mangkok angkringan --}}
                    <path d="M9 26 C9 26 10.5 17 18 17 C25.5 17 27 26 27 26" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    <line x1="7" y1="26" x2="29" y2="26" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    {{-- Uap (steam) --}}
                    <path d="M13 13 C13 11.5 14 10.5 14 9 C14 10.5 15 11.5 15 13" stroke="rgba(175,221,229,0.8)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M17.5 11 C17.5 9.5 18.5 8.5 18.5 7 C18.5 8.5 19.5 9.5 19.5 11" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M22 13 C22 11.5 23 10.5 23 9 C23 10.5 24 11.5 24 13" stroke="rgba(175,221,229,0.8)" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="login-app-name">Sikasir Angkringan</div>
            <div class="login-app-tagline">Sistem Kasir Usaha Kuliner Modern</div>
        </div>

        {{-- Form section --}}
        <div class="login-form-body">

            <div class="login-form-heading">Masuk</div>

            {{-- Error --}}
            @if ($errors->any())
                <div class="login-error" role="alert">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" autocomplete="on">
                @csrf

                {{-- Anchor for browser credential manager --}}
                <input type="text" name="_u" style="display:none;position:absolute;" autocomplete="username" tabindex="-1" aria-hidden="true">
                <input type="password" name="_p" style="display:none;position:absolute;" autocomplete="current-password" tabindex="-1" aria-hidden="true">

                <div style="margin-bottom: 1rem;">
                    <label class="form-label" for="username">Username</label>
                    <input
                        class="login-input"
                        id="username"
                        name="username"
                        type="text"
                        autocomplete="username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        placeholder="Masukkan username"
                        spellcheck="false"
                    >
                </div>

                <div style="margin-bottom: 0.25rem;">
                    <label class="form-label" for="password">Password</label>
                    <input
                        class="login-input"
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        placeholder="••••••••"
                    >
                </div>

                <button class="btn-login" type="submit" id="btn-login">
                    Masuk ke Sikasir
                </button>
            </form>

        </div>

        <div class="login-card-footer">
            &copy; {{ date('Y') }} Sikasir Angkringan &nbsp;&mdash;&nbsp; Kelola usaha lebih cerdas
        </div>

    </div>
</div>
@endsection
