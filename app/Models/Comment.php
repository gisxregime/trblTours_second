<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tour_listing_id',
        'tourist_request_id',
        'user_id',
        'body',
    ];

    public function tourListing(): BelongsTo
    {
        return $this->belongsTo(TourListing::class);
    }

    public function touristRequest(): BelongsTo
    {
        return $this->belongsTo(TouristRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
