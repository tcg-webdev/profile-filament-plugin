<?php

namespace Rawilk\ProfileFilament\Contracts;
use Illuminate\Contracts\Auth\Authenticatable as User;

interface TextOtpService
{
    public function generateSecretKey(): string;

    public function verify(string $originalCode, string $code): bool;

    public function sendCode(string $phoneNumber): mixed;

    public function notifyChallengedUser(User $user):void;
}
