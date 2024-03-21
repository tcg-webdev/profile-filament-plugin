<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Actions\TextOtps;

use Rawilk\ProfileFilament\Contracts\TextOtps\DeleteTextOtpCodeAction;
use Rawilk\ProfileFilament\Contracts\TwoFactor\MarkTwoFactorDisabledAction;
use Rawilk\ProfileFilament\Events\TextOtps\TwoFactorTextRemoved;
use Rawilk\ProfileFilament\Models\TextOtpCode;

class DeleteTwoFactorTextAction implements DeleteTextOtpCodeAction
{
    public function __invoke(TextOtpCode $phone): void
    {
        $phone->delete();

        app(MarkTwoFactorDisabledAction::class)($phone->user);

        TwoFactorTextRemoved::dispatch($phone->user, $phone);
    }
}
