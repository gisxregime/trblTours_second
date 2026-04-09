<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'tourist_id',
        'guide_id',
        'tour_id',
        'last_message_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function tourist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tourist_id');
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
