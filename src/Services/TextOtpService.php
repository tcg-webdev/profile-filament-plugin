<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Services;

use Illuminate\Contracts\Cache\Repository as Cache;

use Illuminate\Support\Facades\Log;
use Rawilk\ProfileFilament\Contracts\TextOtpService as TextOtpServiceContract;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TextOtpService implements TextOtpServiceContract
{
    public function __construct(protected Client $client, protected string $senderPhoneNumber)
    {
    }

    public function generateSecretKey(): string
    {
        return $this->otpCodeGenerator(6);
    }

    public function verify(string $originalCode, string $code): bool
    {

        return $originalCode === $code;
    }

    private function otpCodeGenerator(int $digitNumber = 4): string
    {
        $charactersBase = '0135792468';
        $otpCode = $this->generateRandomString($charactersBase, $digitNumber);

        if ($otpCode[0] === '0') {
            $otpCode[0] = $this->getRandomNonZeroCharacter($charactersBase);
        }

        return $otpCode;
    }


    private function generateRandomString(string $characters, int $length): string
    {
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCharacter = $characters[rand(0, $charactersLength - 1)];
            $randomString .= $randomCharacter;
        }

        return $randomString;
    }


    private function getRandomNonZeroCharacter(string $characters): string
    {
        $nonZeroCharacters = trim($characters, '0');

        return $nonZeroCharacters[rand(0, strlen($nonZeroCharacters) - 1)];
    }

    public function sendCode(string $phoneNumber): mixed
    {
        $code = $this->generateSecretKey();
        try {
            $this->client->messages->create(
            // The number you'd like to send the message to
                $phoneNumber,
                [
                    'from' => $this->senderPhoneNumber,
                    'body' => __('profile-filament::messages.mfa_challenge.text.code_message', ['code' => $code]),
                ]
            );

            return $code;
        } catch(TwilioException $ex) {
            Log::error($ex->getMessage());
        }

        return false;
    }
}
