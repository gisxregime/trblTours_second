<?php

namespace App\Models;

use Database\Factories\GuideStoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideStory extends Model
{
    /** @use HasFactory<GuideStoryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'guide_id',
        'image_path',
        'image_paths',
        'caption',
        'content',
        'likes_count',
        'liked_by',
        'messages',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'image_paths' => 'array',
            'liked_by' => 'array',
            'messages' => 'array',
        ];
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
}
