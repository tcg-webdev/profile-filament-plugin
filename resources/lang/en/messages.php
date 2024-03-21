<?php

declare(strict_types=1);

return [

    'alert' => [
        'dismiss' => 'Dismiss',
    ],

    'mfa_challenge' => [
        'invalid_challenged_user' => 'Your user account could not be verified.',
    ],

    'sudo_challenge' => [

        'title' => 'Confirm access',
        'tip' => "**Tip:** You are entering sudo mode. After you've performed a sudo-protected action, you'll only be asked to re-authenticate again after a few hours of inactivity.",
        'cancel_button' => 'Cancel',
        'signed_in_as' => 'Signed in as: **:handle**',
        'expired' => 'Your sudo session has expired. Please refresh the page to try again.',

        'alternative_heading' => 'Having problems?',

        'totp' => [
            'use_label' => 'Use your authenticator app',
            'heading' => 'Authentication code',
            'help_text' => 'Open your two-factor authenticator (TOTP) app or browser extension to view your authentication code.',
            'placeholder' => '6-digit code',
            'invalid' => 'The code you entered is invalid.',
            'submit' => 'Verify',
        ],

        'text' => [
            'use_label' => 'Use your phone',
            'heading' => 'Authentication code',
            'help_text' => 'Please check your phone for SMS message containing the authentication code.',
            'placeholder' => '6-digit code',
            'invalid' => 'The code you entered is invalid.',
            'submit' => 'Verify',
        ],

        'webauthn' => [
            'use_label' => 'Use your security key',
            'use_label_including_passkeys' => 'Use your passkey or security key',
            'heading' => 'Security key',
            'heading_including_passkeys' => 'Passkey or security key',
            'waiting' => 'Waiting for input from browser interaction...',
            'failed' => 'Authentication failed.',
            'retry' => 'Retry security key',
            'retry_including_passkeys' => 'Retry passkey or security key',
            'submit' => 'Use security key',
            'submit_including_passkeys' => 'Use passkey or security key',
            'hint' => 'When you are ready, authenticate using the button below.',
            'invalid' => 'Authentication failed.',
        ],

        'password' => [
            'use_label' => 'Use your password',
            'input_label' => 'Your password',
            'submit' => 'Confirm',
            'invalid' => 'Incorrect password.',
        ],

    ],

    'masked_value' => [
        'reveal_button' => 'Reveal',
    ],

];
