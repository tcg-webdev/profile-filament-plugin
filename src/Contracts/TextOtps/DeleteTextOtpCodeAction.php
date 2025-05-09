<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Contracts\TextOtps;

use Rawilk\ProfileFilament\Models\TextOtpCode;

interface DeleteTextOtpCodeAction
{
    public function __invoke(TextOtpCode $phone);
}
