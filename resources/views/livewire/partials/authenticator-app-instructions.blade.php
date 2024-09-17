<div class="mt-4">
    <p>
        {{ \Rawilk\ProfileFilament\renderMarkdown(__('profile-filament::pages/security.mfa.app.form_intro', ['google' => 'https://support.google.com/accounts/answer/1066447?hl=en&co=GENIE.Platform%3DAndroid','authy' => 'https://authy.com/guides/', 'microsoft' => 'https://www.microsoft.com/en-us/account/authenticator/', 'one_password' => 'https://support.1password.com/one-time-passwords/'])) }}
    </p>

    <p class="mt-3 font-bold dark:text-white text-gray-600">{{ __('profile-filament::pages/security.mfa.app.scan_title') }}</p>

    <p class="mt-3">
        {{ \Rawilk\ProfileFilament\renderMarkdown(__('profile-filament::pages/security.mfa.app.scan_instructions')) }}
    </p>

    @if ($qrCodeUrl)
        <div class="mt-5">
            <div class="p-3 inline-block rounded-md bg-gray-100 dark:bg-white">
                {{ new \Illuminate\Support\HtmlString($this->authenticatorService->qrCodeSvg($qrCodeUrl)) }}
            </div>
        </div>
    @endif

    <p class="mt-3">
       {{ \Rawilk\ProfileFilament\renderMarkdown(__('profile-filament::pages/security.mfa.app.enter_code_instructions')) }}
    </p>
</div>
