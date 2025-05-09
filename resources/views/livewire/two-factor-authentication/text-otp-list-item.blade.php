<div class="py-3 flex justify-between items-center gap-x-3">
    @if ($phone)
        <div>
            <div>
                <span>{{ $phone->number }}</span>
                <span class="text-gray-500 dark:text-gray-400 text-xs">
                    {{ $phone->registered_at }}
                </span>

                <x-filament-actions::modals />
            </div>

            <div>
                <span class="text-gray-500 dark:text-gray-400 text-xs">
                    {{ $phone->last_used }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-x-2">
            {{ $this->deleteAction }}
        </div>
    @endif
</div>
