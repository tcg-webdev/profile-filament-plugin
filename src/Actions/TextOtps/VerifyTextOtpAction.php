<?php

namespace Rawilk\ProfileFilament\Actions\TextOtps;

use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Support\Facades\Log;
use Rawilk\ProfileFilament\Contracts\TextOtps\VerifyTextOtpAction as VerifyTextOtpContract;
use Rawilk\ProfileFilament\Contracts\TextOtpService as TextOtpServiceContract;
use Twilio\Exceptions\TwilioException;

class VerifyTextOtpAction implements VerifyTextOtpContract
{
    public function __invoke(User $user, string $number)
    {
        try {
            if ($code = app(TextOtpServiceContract::class)->sendCode($number)) {
                cache()->put($user::hasTextValidationKey($user), $code, now()->addMinutes(10));
                Notification::make()
                            ->success()
                            ->title(__('profile-filament::messages.text.code_sent', ['number' => $number]))
                            ->send();
            }
        } catch(TwilioException $ex) {
            Log::error($ex->getMessage());
            Notification::make()
                        ->warning()
                        ->title(__('profile-filament::messages.text.code_not_sent', ['number' => $number]))
                        ->send();
        }
    }
}
