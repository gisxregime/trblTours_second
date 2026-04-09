<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideEarning extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'guide_id',
        'booking_id',
        'gross_amount',
        'platform_fee',
        'fiesta_surcharge',
        'net_amount',
        'earning_date',
        'payout_status',
        'paid_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'fiesta_surcharge' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'earning_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
