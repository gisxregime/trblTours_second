<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    /** @use HasFactory<\Database\Factories\TourFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'created_by',
        'name',
        'region',
        'description',
        'duration',
        'price',
        'max_people',
        'image_url',
        'activities',
        'is_featured',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
        ];
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
