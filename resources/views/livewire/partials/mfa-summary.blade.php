@php
    use Rawilk\ProfileFilament\Enums\Livewire\MfaEvent;
@endphp

<div id="mfa-summary" class="border rounded-md dark:border-gray-700 divide-y dark:divide-gray-700">
    <x-profile-filament::box-header>
        {{ __('profile-filament::pages/security.mfa.methods_title') }}
    </x-profile-filament::box-header>

    @if ($this->canAuthenticatorApps)
        <x-profile-filament::box-row
            icon="heroicon-o-device-phone-mobile"
            icon-alias="mfa::totp"
            id="totp-list-container"
            device-count-translation="profile-filament::pages/security.mfa.app.device_count"
            :label="__('profile-filament::pages/security.mfa.app.title')"
            :description="__('profile-filament::pages/security.mfa.app.description')"
            :device-count="$this->authenticatorApps->count()"
        >
            <x-slot:button>
                {{ $this->toggleTotpAction }}
            </x-slot:button>

            <livewire:authenticator-app-form
                :show="$showAuthenticatorAppForm"
                :authenticator-apps="$this->authenticatorApps"
            />
        </x-profile-filament::box-row>
    @endif
    @if ($this->canTextOtps)
        <x-profile-filament::box-row
            icon="heroicon-o-chat-bubble-left-ellipsis"
            icon-alias="mfa::phone"
            id="texts-list-container"
            device-count-translation="profile-filament::pages/security.mfa.text.device_count"
            :label="__('profile-filament::pages/security.mfa.text.title')"
            :description="__('profile-filament::pages/security.mfa.text.description')"
            :device-count="$this->textOtps->count()"
        >
            <x-slot:button>
                {{ $this->toggleTextAction }}
            </x-slot:button>

            <livewire:text-otp-form
                :show="$showTextOtpForm"
                :textOtps="$this->textOtps"
            />
        </x-profile-filament::box-row>
    @endif

    @if ($this->canWebauthn)
        <x-profile-filament::box-row
            icon="heroicon-o-shield-exclamation"
            icon-alias="mfa::webauthn"
            id="webauthn-list-container"
            device-count-translation="profile-filament::pages/security.mfa.webauthn.device_count"
            :label="__('profile-filament::pages/security.mfa.webauthn.title')"
            :description="__('profile-filament::pages/security.mfa.webauthn.description')"
            :device-count="$this->webauthnKeys->count()"
        >
            <x-slot:button>
                {{ $this->toggleWebauthnAction }}
            </x-slot:button>

            <livewire:webauthn-keys
                :webauthn-keys="$this->webauthnKeys"
            />
        </x-profile-filament::box-row>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook('profile-filament::mfa.methods.after') }}

    <x-profile-filament::box-header>
        {{ __('profile-filament::pages/security.mfa.recovery_title') }}
    </x-profile-filament::box-header>

    <x-profile-filament::box-row
        icon="heroicon-o-key"
        icon-alias="mfa::recovery-codes"
        :label="__('profile-filament::pages/security.mfa.recovery_codes.title')"
        :description="__('profile-filament::pages/security.mfa.recovery_codes.description')"
        id="recovery-codes-container"
    >
        <x-slot:button>
            {{ $this->toggleRecoveryCodesAction }}
        </x-slot:button>

        @if ($showRecoveryCodes)
            <livewire:recovery-codes />
        @endif
    </x-profile-filament::box-row>
</div>

@include('profile-filament::livewire.partials.recovery-codes-modal')
