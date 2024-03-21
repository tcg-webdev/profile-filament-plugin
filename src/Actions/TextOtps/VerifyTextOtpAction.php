<?php

namespace Rawilk\ProfileFilament\Actions\TextOtps;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\ProfileFilament\Contracts\TextOtps\VerifyTextOtpAction as VerifyTextOtpContract;
use Rawilk\ProfileFilament\Contracts\TextOtpService as TextOtpServiceContract;

class VerifyTextOtpAction implements VerifyTextOtpContract
{
    public function __invoke(User $user, string $number)
    {
        if($code = app(TextOtpServiceContract::class)->sendOtpCode($number)) {
           cache()->set($user->hasTextValidationKey(), $code);
            Notification::make()
                        ->success()
                        ->title(__('profile-filament::messages.text.code_sent', ['number' => $number]))
                        ->send();
        }
    }
}
