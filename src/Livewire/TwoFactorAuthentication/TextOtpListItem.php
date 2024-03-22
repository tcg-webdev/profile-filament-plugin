<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Livewire\TwoFactorAuthentication;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Rawilk\ProfileFilament\Concerns\Sudo\UsesSudoChallengeAction;
use Rawilk\ProfileFilament\Contracts\TextOtps\DeleteTextOtpCodeAction;
use Rawilk\ProfileFilament\Enums\Livewire\MfaEvent;
use Rawilk\ProfileFilament\Livewire\ProfileComponent;
use Rawilk\ProfileFilament\Models\TextOtpCode;

class TextOtpListItem extends ProfileComponent
{
    use UsesSudoChallengeAction;

    public ?TextOtpCode $phone;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('profile-filament::pages/security.mfa.text.actions.delete.trigger_label', ['number' => $this->phone->number]))
            ->icon(FilamentIcon::resolve('actions::delete-action') ?? 'heroicon-o-trash')
            ->button()
            ->hiddenLabel()
            ->tooltip(__('profile-filament::pages/security.mfa.text.actions.delete.trigger_tooltip'))
            ->color('danger')
            ->size('sm')
            ->outlined()
            ->action(function (DeleteTextOtpCodeAction $deleter) {
                $this->ensureSudoIsActive(returnAction: 'delete');

                $this->authorize('delete', $this->phone);

                $deleter($this->phone);

                Notification::make()
                    ->title(__('profile-filament::pages/security.mfa.text.actions.delete.success_message', ['number' => $this->phone->number]))
                    ->success()
                    ->send();

                $this->dispatch(MfaEvent::TextDeleted->value, appId: $this->phone->getKey());

                $this->phone = null;
            })
            ->requiresConfirmation()
            ->modalHeading(__('profile-filament::pages/security.mfa.text.actions.delete.title'))
            ->modalIcon(fn () => FilamentIcon::resolve('actions::delete-action.modal') ?? 'heroicon-o-trash')
            ->modalDescription(
                new HtmlString(
                    Str::inlineMarkdown(__('profile-filament::pages/security.mfa.text.actions.delete.description',
                        ['number' => $this->phone->number]))
                )
            )
            ->modalSubmitActionLabel(__('profile-filament::pages/security.mfa.text.actions.delete.confirm'))
            ->extraAttributes([
                'title' => '',
            ])
            ->mountUsing(function () {
                $this->ensureSudoIsActive(returnAction: 'delete');
            });
    }

    protected function view(): string
    {
        return 'profile-filament::livewire.two-factor-authentication.text-otp-list-item';
    }
}
