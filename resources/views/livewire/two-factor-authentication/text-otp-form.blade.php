<div id="text-otp-form">
    @if ($show)
        @includeWhen($textOtps->isNotEmpty() && ! $showForm, 'profile-filament::livewire.partials.text-otp-list')

        @includeWhen($showForm, 'profile-filament::livewire.partials.add-text-otp')
    @endif
</div>
