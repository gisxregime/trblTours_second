<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TouristRequest extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tour_listing_id',
        'tourist_id',
        'message',
        'status',
    ];

    public function tourListing(): BelongsTo
    {
        return $this->belongsTo(TourListing::class);
    }

    public function tourist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tourist_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
