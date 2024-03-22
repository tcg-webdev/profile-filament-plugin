<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Livewire\TwoFactorAuthentication;

use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use Illuminate\Support\Timebox;
use Illuminate\Validation\Rules\Unique;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Rawilk\ProfileFilament\Actions\TextOtps\ConfirmTwoFactorTextAction;
use Rawilk\ProfileFilament\Actions\TextOtps\VerifyTextOtpAction;
use Rawilk\ProfileFilament\Concerns\Sudo\UsesSudoChallengeAction;
use Rawilk\ProfileFilament\Contracts\AuthenticatorApps\ConfirmTwoFactorAppAction;
use Rawilk\ProfileFilament\Contracts\AuthenticatorAppService;
use Rawilk\ProfileFilament\Contracts\TextOtpService;
use Rawilk\ProfileFilament\Enums\Livewire\MfaEvent;
use Rawilk\ProfileFilament\Livewire\ProfileComponent;

/**
 * @property-read \Rawilk\ProfileFilament\Contracts\TextOtpService $authenticatorService
 * @property-read bool $showCodeError
 * @property-read \Illuminate\Contracts\Auth\Authenticatable $user
 * @property-read \Illuminate\Support\Collection $sortedTextOtps
 * @property-read \Filament\Forms\Form $form
 */
class TextOtpForm extends ProfileComponent
{
    use UsesSudoChallengeAction;

    #[Locked]
    public bool $show = false;

    #[Locked]
    public bool $showForm = false;

    public string $code = '';

    public string $number= '';

    #[Locked]
    public bool $codeValid = false;

    /** @var \Illuminate\Support\Collection<int, \Rawilk\ProfileFilament\Models\TextOtpCode> */
    #[Reactive]
    public Collection $textOtps;

    #[Computed]
    public function sortedTextOtps(): Collection
    {
        return $this->textOtps
            ->sortByDesc('created_at');
    }

    #[Computed]
    public function user(): Authenticatable
    {
        return filament()->auth()->user();
    }

    #[Computed]
    public function authenticatorService(): TextOtpService
    {
        return app(TextOtpService::class);
    }

    #[Computed]
    public function showCodeError(): bool
    {
        return filled($this->code) && ! $this->codeValid;
    }

    #[On(MfaEvent::ShowTextForm->value)]
    public function showTextOtps(): void
    {
        $this->reset('number', 'code');

        $this->show = true;
        $this->showForm = $this->textOtps->isEmpty();

        if ($this->showForm) {
            $this->showAddForm();
        }
    }

    public function showAddForm(): void
    {
        $this->reset('code', 'codeValid');

        $this->number = __('profile-filament::pages/security.mfa.text.default_number');

        $this->showForm = true;
    }


    public function verifyPhoneNumber():void
    {
        try {
            $this->ensureSudoIsActive(returnAction: 'add');
        } catch (Halt) {
            Notification::make()
                        ->danger()
                        ->title(__('profile-filament::messages.sudo_challenge.expired'))
                        ->send();

            return;
        }

        $data = $this->form->getState();

        $action = new VerifyTextOtpAction();

        $action(filament()->auth()->user(), $data['number']);
    }

    public function confirm(ConfirmTwoFactorTextAction $action): void
    {
        try {
            $this->ensureSudoIsActive(returnAction: 'add');
        } catch (Halt) {
            Notification::make()
                ->danger()
                ->title(__('profile-filament::messages.sudo_challenge.expired'))
                ->send();

            return;
        }

        App::make(Timebox::class)->call(callback: function (Timebox $timebox) use ($action) {
            $data = $this->form->getState();
            $this->ensureCodeIsValid($data['code']);
            if (! $this->codeValid) {
                return;
            }

            // Flag for our listener in parent component to know if recovery codes
            // should be shown to the user or not.
            /** @phpstan-ignore-next-line */
            $enabledMfa = ! $this->user->two_factor_enabled;

            $action(filament()->auth()->user(), $data['number']);

            $this->cancelForm();

            $this->dispatch(MfaEvent::TextAdded->value, enabledMfa: $enabledMfa);

            $timebox->returnEarly();
        }, microseconds: 300 * 1000);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNumberField(),
                $this->verifyActionButton(),
                $this->getCodeField(),
            ]);
    }

    #[On(MfaEvent::HideTextList->value)]
    public function hideList(): void
    {
        $this->show = false;
    }

    public function addAction(): Action
    {
        return Action::make('add')
            ->color('gray')
            ->action(fn () => $this->showAddForm())
            ->label(__('profile-filament::pages/security.mfa.text.add_another_text_button'))
            ->mountUsing(function () {
                $this->ensureSudoIsActive(returnAction: 'add');
            });
    }

    public function verifyActionButton(): Actions
    {
        return Actions::make([
            FormAction::make('verify')
                     ->color('green')
                     ->action(fn () => $this->verifyPhoneNumber())
                     ->label(__('profile-filament::pages/security.mfa.text.verify_button')),
            ]);
    }

    public function submitAction(): Action
    {
        return Action::make('submit')
            ->label(__('profile-filament::pages/security.mfa.text.submit_code_confirmation'))
            ->disabled(fn () => ! $this->codeValid)
            ->submit('confirm');
    }

    public function cancelAction(): Action
    {
        return Action::make('cancel')
            ->label(__('profile-filament::pages/security.mfa.text.cancel_code_confirmation'))
            ->color('gray')
            ->action(fn () => $this->cancelForm());
    }

    protected function view(): string
    {
        return 'profile-filament::livewire.two-factor-authentication.text-otp-form';
    }

    protected function getNumberField(): Component
    {
        return TextInput::make('number')
            ->label(__('profile-filament::pages/security.mfa.text.phone_number'))
            ->placeholder(__('profile-filament::pages/security.mfa.text.default_number'))
            ->rules(['phone'])
            ->required()
            ->maxlength(255)
            ->autocomplete('off')
            ->maxWidth('xs')
            ->unique(
                table: config('profile-filament.table_names.text_otp_code'),
                modifyRuleUsing: function (Unique $rule) {
                    $rule->where('user_id', filament()->auth()->id());
                },
            )
            ->helperText(__('profile-filament::pages/security.mfa.text.phone_number_help'));
    }

    protected function getCodeField(): Component
    {
        return TextInput::make('code')
            ->label(__('profile-filament::pages/security.mfa.text.code_confirmation_input'))
            ->placeholder(__('profile-filament::pages/security.mfa.text.code_confirmation_placeholder'))
            ->maxWidth('xs')
            ->autocomplete('off')
            ->debounce()
            ->visible(function() {
                return cache()->has(auth()->user()::hasTextValidationKey(auth()->user()));
            })
            ->required()
            ->extraInputAttributes([
                'pattern' => '[0-9]{6}',
            ])
            ->afterStateUpdated(function (?string $state) {
                if (blank($state)) {
                    $this->codeValid = false;

                    return;
                }

                $this->ensureCodeIsValid($state);
            });
    }


    protected function cancelForm(): void
    {
        $this->reset('code', 'number', 'showForm');

        $user = filament()->auth()->user();
        cache()->delete($user::hasTextValidationKey($user));

        if ($this->textOtps->isEmpty()) {
            $this->show = false;
            $this->dispatch(MfaEvent::HideTextForm->value);
        }
    }

    protected function isCodeValid(string $code): bool
    {
        $user = filament()->auth()->user();
        $originalCode = cache()->get($user::hasTextValidationKey($user));

        return $this->authenticatorService->verify(
            originalCode: $originalCode,
            code: $code,
        );
    }

    protected function ensureCodeIsValid(string $code): void
    {
        $this->codeValid = $this->isCodeValid($code);
    }
}
