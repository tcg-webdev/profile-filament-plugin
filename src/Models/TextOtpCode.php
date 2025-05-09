<?php

declare(strict_types=1);

namespace Rawilk\ProfileFilament\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Rawilk\ProfileFilament\Facades\ProfileFilament;

use function Rawilk\ProfileFilament\wrapDateInTimeTag;

/**
 * @property int $id
 * @property int $user_id
 * @property string $number
 * @property string $code
 * @property null|\Illuminate\Support\Carbon $last_used_at
 * @property null|\Illuminate\Support\Carbon $created_at
 * @property null|\Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Support\HtmlString $last_used
 * @property-read \Illuminate\Support\HtmlString $registered_at
 */
class TextOtpCode extends Model
{
    use HasFactory;

    protected $hidden = [
        'code',
    ];

    protected $casts = [
        'code' => 'encrypted',
        'valid_to' => 'immutable_datetime',
        'last_used_at' => 'immutable_datetime',
    ];

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('profile-filament.table_names.text_otp_code');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function lastUsed(): Attribute
    {
        return Attribute::make(
            get: function () {
                $date = blank($this->last_used_at)
                    ? __('profile-filament::pages/security.mfa.method_never_used')
                    : wrapDateInTimeTag($this->last_used_at->tz(ProfileFilament::userTimezone()), 'M d, Y g:i a');

                $translation = __('profile-filament::pages/security.mfa.method_last_used_date', ['date' => $date]);

                return new HtmlString(Str::inlineMarkdown($translation));
            },
        )->shouldCache();
    }

    protected function registeredAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $date = $this->created_at->tz(ProfileFilament::userTimezone());

                $translation = __('profile-filament::pages/security.mfa.method_registration_date', ['date' => wrapDateInTimeTag($date)]);

                return new HtmlString(Str::inlineMarkdown($translation));
            },
        )->shouldCache();
    }
}
