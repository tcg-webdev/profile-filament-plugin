<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Actions\TextOtps;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\ProfileFilament\Contracts\TextOtps\ConfirmTwoFactorTextAction as
    ConfirmTwoFactorTextActionContract;
use Rawilk\ProfileFilament\Contracts\TwoFactor\MarkTwoFactorEnabledAction;
use Rawilk\ProfileFilament\Events\TextOtps\TwoFactorTextAdded;
use Rawilk\ProfileFilament\Models\TextOtpCode;

class ConfirmTwoFactorTextAction implements ConfirmTwoFactorTextActionContract
{
    /** @var class-string<\Illuminate\Database\Eloquent\Model> */
    protected string $model;

    public function __construct()
    {
        $this->model = config('profile-filament.models.text_otp_code');
    }

    public function __invoke(User $user, string $number)
    {
        $authenticator = tap(app($this->model)::make(), function (TextOtpCode $authenticator) use ($user, $number) {
            $authenticator->fill([
                'number' => $number,
                'user_id' => $user->getAuthIdentifier(),
            ])->save();
        });

        cache()->forget($user::hasTextValidationKey($user));

        app(MarkTwoFactorEnabledAction::class)($user);

        TwoFactorTextAdded::dispatch($user, $authenticator);
    }
}
