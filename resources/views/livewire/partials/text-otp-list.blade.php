<div id="text-otp-list" class="divide-y divide-gray-300 dark:divide-gray-600">
    @foreach ($this->sortedTextOtps as $registeredText)
        <livewire:text-otp-list-item
            :text="$registeredText"
            :key="'textOtp' . $registeredText->id"
        />
    @endforeach

    <div class="py-3">
        {{ $this->addAction }}
    </div>
</div>

<x-filament-actions::modals />
