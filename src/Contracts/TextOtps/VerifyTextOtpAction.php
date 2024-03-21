<?php

namespace Rawilk\ProfileFilament\Contracts\TextOtps;

use Illuminate\Contracts\Auth\Authenticatable as User;
interface VerifyTextOtpAction
{
    public function __invoke(User $user, string $number);
}
