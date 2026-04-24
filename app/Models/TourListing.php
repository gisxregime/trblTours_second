<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourListing extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'guide_id',
        'service_location_id',
        'title',
        'description',
        'price',
        'status',
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function serviceLocation(): BelongsTo
    {
        return $this->belongsTo(ServiceLocation::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(TouristRequest::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
