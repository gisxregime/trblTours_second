<?php

namespace App\Livewire\Guide;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\GuideStory;
use App\Models\Tour;
use App\Models\TourReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GuideProfile extends Component
{
    public function render()
    {
        $user = Auth::user();
        abort_if($user === null, 401);
        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        $profile = $this->getGuideProfile((int) $user->id);

        $completedBookings = 0;
        if (Schema::hasTable('bookings')) {
            $completedBookingsQuery = Booking::query()->where('status', 'completed');

            if (Schema::hasColumn('bookings', 'booking_request_id') && Schema::hasTable('booking_requests')) {
                $completedBookingsQuery->whereIn(
                    'booking_request_id',
                    BookingRequest::query()->select('id')->where('guide_id', $user->id)
                );
            } else {
                $completedBookingsQuery->whereRaw('1 = 0');
            }

            $completedBookings = $completedBookingsQuery->count();
        }

        $averageRating = Schema::hasTable('tour_reviews')
            ? round((float) TourReview::query()->where('guide_id', $user->id)->avg('rating'), 1)
            : 0.0;

        // Load guide's posts (stories) when the table exists.
        $posts = Schema::hasTable((new GuideStory)->getTable())
            ? $user->guideStories()->orderByDesc('created_at')->get()
            : collect();

        // Load guide's own tours so the dashboard reflects every update immediately.
        $toursQuery = Tour::query()->where('guide_id', $user->id);

        $tours = $toursQuery->orderByDesc('created_at')->get();

        // Calculate statistics
        $stats = [
            'totalToursCompleted' => $completedBookings,
            'totalReviews' => Schema::hasTable('tour_reviews')
                ? TourReview::where('guide_id', $user->id)->count()
                : 0,
            'averageRating' => $averageRating,
        ];

        return view('livewire.guide.guide-profile', [
            'guide' => [
                'full_name' => $user->name,
                'display_name' => $user->display_name ?? $user->full_name ?? $user->name,
                'region' => $user->region,
                'city_municipality' => $this->firstFilledValue($profile, ['city_municipality', 'city', 'municipality']),
                'barangay' => $this->firstFilledValue($profile, ['barangay', 'barangay_name']),
                'bio' => $this->firstFilledValue($profile, ['bio'], (string) ($user->bio ?? 'No bio yet.')),
                'phone_number' => (string) ($profile['phone_number'] ?? ''),
                'date_of_birth' => (string) ($profile['date_of_birth'] ?? ''),
                'profile_photo_path' => $this->firstFilledValue($profile, ['profile_photo_path', 'profile_picture', 'avatar_path']),
                'cover_photo_path' => $this->firstFilledValue($profile, ['cover_photo_path', 'cover_image_path']),
                'specialty' => $user->specialty,
                'years_of_experience' => (string) ($profile['years_of_experience'] ?? ''),
            ],
            'completedBookings' => $completedBookings,
            'averageRating' => $averageRating,
            'documentStatus' => (string) ($profile['approval_status'] ?? 'pending'),
            'verificationStatus' => $this->verificationStatus($profile),
            'posts' => $posts,
            'tours' => $tours,
            'stats' => $stats,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function getGuideProfile(int $userId): array
    {
        if (! Schema::hasTable('tour_guides_profile')) {
            return [];
        }

        return (array) (DB::table('tour_guides_profile')->where('user_id', $userId)->first() ?? []);
    }

    /**
     * @param  array<string, mixed>  $profile
     */
    private function verificationStatus(array $profile): string
    {
        if (($profile['approval_status'] ?? null) === 'approved') {
            return 'approved';
        }

        if (($profile['approval_status'] ?? null) === 'rejected') {
            return 'rejected';
        }

        return 'pending';
    }

    /**
     * @param  array<string, mixed>  $values
     * @param  array<int, string>  $keys
     */
    private function firstFilledValue(array $values, array $keys, string $fallback = ''): string
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $values)) {
                continue;
            }

            $value = trim((string) $values[$key]);
            if ($value !== '') {
                return $value;
            }
        }

        return $fallback;
    }
}
