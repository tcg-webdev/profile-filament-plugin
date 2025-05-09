<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Authorizable as User;
use Rawilk\ProfileFilament\Models\TextOtpCode;

class TextOtpCodePolicy
{
    use HandlesAuthorization;

    public function edit(User $user, TextOtpCode $textOtpCode): Response
    {
        return $user->id === $textOtpCode->user_id
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, TextOtpCode $textOtpCode): Response
    {
        return $user->id === $textOtpCode->user_id
            ? Response::allow()
            : Response::deny();
    }
}
