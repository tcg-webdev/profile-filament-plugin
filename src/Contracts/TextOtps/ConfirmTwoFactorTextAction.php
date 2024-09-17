<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Contracts\TextOtps;

use Illuminate\Contracts\Auth\Authenticatable as User;

interface ConfirmTwoFactorTextAction
{
    public function __invoke(User $user, string $number);
}
