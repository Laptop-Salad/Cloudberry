<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    // NEW — controls expanded login box
    public bool $showForm = false;

    public function revealForm(): void
    {
        $this->showForm = true;
    }

    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            $this->redirect(route('two-factor.login'), navigate: true);
            return;
        }

        Auth::login($user, $this->remember);
        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email, 'password' => $this->password]);

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

        <!-- login ui -->

<div class="login-page">
    <div class="logo-wrapper">
        <img src="/Logo.png" class="login-logo" alt="Logo">
    </div>

    @if (! $this->showForm)
        <button
                wire:click="revealForm"
                class="collapsed-login-button"
        >
            LOGIN
        </button>
    @endif

    @if ($this->showForm)
        <div
                x-data
                x-cloak
                x-show="@entangle('showForm')"
                x-transition:enter="transition-all duration-500 ease-out"
                x-transition:enter-start="opacity-0 scale-y-0 -translate-y-4"
                x-transition:enter-end="opacity-100 scale-y-100 translate-y-0"
                class="login-box expanding-box"
        >

            <h2 class="login-title">LOGIN</h2>

            <x-auth-session-status class="text-center" :status="session('status')" />

            @if ($errors->any())
                <div class="login-error" style="color:red; text-align:center; margin-bottom:10px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" wire:submit="login" class="login-form">
                @csrf

                <flux:input
                        wire:model="email"
                        placeholder="EMAIL"
                        type="email"
                        required
                        autocomplete="email"
                />

                <flux:input
                        wire:model="password"
                        placeholder="PASSWORD"
                        type="password"
                        required
                        autocomplete="current-password"
                        viewable
                />

                <div class="login-button-wrapper">
                    <flux:button type="submit" class="login-button">
                        LOGIN
                    </flux:button>
                </div>

            </form>

        </div>
    @endif

</div>
