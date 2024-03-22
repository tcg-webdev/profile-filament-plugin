<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Events\TextOtps;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\ProfileFilament\Events\ProfileFilamentEvent;
use Rawilk\ProfileFilament\Models\TextOtpCode;

final class TwoFactorTextAdded extends ProfileFilamentEvent
{
    public function __construct(public User $user, public TextOtpCode $phone)
    {
    }
}
