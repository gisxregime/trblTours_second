<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourGuideProfile extends Model
{
    protected $table = 'tour_guides_profile';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'phone_number',
        'nationality',
        'date_of_birth',
        'years_of_experience',
        'bio',
        'government_id_type',
        'government_id_number',
        'id_front_path',
        'id_back_path',
        'selfie_path',
        'tour_guide_cert_number',
        'nbi_clearance_number',
        'nbi_clearance_path',
        'barangay_clearance_number',
        'barangay_clearance_path',
        'nbi_clearance_validated',
        'id_front_verified',
        'id_back_verified',
        'selfie_verified',
        'approved_by_admin',
        'terms_agreed',
        'identity_consent',
        'pending_understood',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'nbi_clearance_validated' => 'boolean',
            'id_front_verified' => 'boolean',
            'id_back_verified' => 'boolean',
            'selfie_verified' => 'boolean',
            'approved_by_admin' => 'boolean',
            'terms_agreed' => 'boolean',
            'identity_consent' => 'boolean',
            'pending_understood' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
