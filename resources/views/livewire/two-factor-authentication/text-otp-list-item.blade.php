<div class="py-3 flex justify-between items-center gap-x-3">
    @if ($text)
        <div>
            <div>
                <span>{{ $text->number }}</span>
                <span class="text-gray-500 dark:text-gray-400 text-xs">
                    {{ $text->registered_at }}
                </span>

                <x-filament-actions::modals />
            </div>

            <div>
                <span class="text-gray-500 dark:text-gray-400 text-xs">
                    {{ $text->last_used }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-x-2">
            {{ $this->deleteAction }}
        </div>
    @endif
</div>
