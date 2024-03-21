<?php

namespace Rawilk\ProfileFilament\Contracts;

interface TextOtpService
{
    public function generateSecretKey(): string;

    public function verify(string $originalCode, string $code): bool;

    public function sendCode(string $phoneNumber): mixed;
}
