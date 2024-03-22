<?php

namespace Rawilk\ProfileFilament\Actions\TextOtps;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\ProfileFilament\Contracts\TextOtps\VerifyTextOtpAction as VerifyTextOtpContract;
use Rawilk\ProfileFilament\Contracts\TextOtpService as TextOtpServiceContract;

class VerifyTextOtpAction implements VerifyTextOtpContract
{
    public function __invoke(User $user, string $number)
    {
        if($code = app(TextOtpServiceContract::class)->sendCode($number)) {
           cache()->put($user::hasTextValidationKey($user), $code, now()->addMinutes(10));
            Notification::make()
                        ->success()
                        ->title(__('profile-filament::messages.text.code_sent', ['number' => $number]))
                        ->send();
        }
    }
}
