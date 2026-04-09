<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->dashboardRouteName());
    }

    public function tourist(): View
    {
        return view('dashboards.tourist');
    }

    public function guide(Request $request): View
    {
        $user = $request->user();

        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        $profile = $this->getGuideProfile((int) $user->id);

        $firstName = $this->firstName($user->full_name ?: $user->name);

        $criteria = [
            ['label' => 'Profile photo uploaded', 'weight' => 15, 'complete' => $this->isFilled($profile, ['profile_photo_path', 'profile_photo', 'avatar_path', 'selfie_path'])],
            ['label' => 'Bio completed (min. 100 characters)', 'weight' => 15, 'complete' => mb_strlen(trim((string) Arr::first([$profile['bio'] ?? null, $user->bio ?? null], fn ($value) => is_string($value)))) >= 100],
            ['label' => 'Languages selected (min. 1)', 'weight' => 10, 'complete' => $this->hasAtLeastOneSelection(Arr::first([$profile['languages'] ?? null, $user->languages ?? null]))],
            ['label' => 'Specialties selected (min. 1)', 'weight' => 10, 'complete' => $this->hasAtLeastOneSelection(Arr::first([$profile['specialties'] ?? null, $profile['specialty'] ?? null, $user->specialty ?? null]))],
            ['label' => 'Government ID uploaded', 'weight' => 15, 'complete' => $this->isFilled($profile, ['id_front_path', 'id_back_path', 'government_id_path'])],
            ['label' => 'NBI Clearance uploaded', 'weight' => 15, 'complete' => $this->isFilled($profile, ['nbi_clearance_path'])],
            ['label' => 'Barangay Clearance uploaded', 'weight' => 10, 'complete' => $this->isFilled($profile, ['barangay_clearance_path'])],
            ['label' => 'Payout details completed', 'weight' => 10, 'complete' => $this->isFilled($profile, ['payout_method', 'payout_account_name', 'payout_account_number', 'bank_account_name', 'bank_account_number', 'gcash_number', 'maya_number'])],
        ];

        $completionPercentage = collect($criteria)
            ->filter(fn (array $criterion): bool => $criterion['complete'])
            ->sum('weight');

        $progressTheme = match (true) {
            $completionPercentage <= 40 => [
                'bar' => 'bg-red-500',
                'badge' => 'bg-red-100 text-red-700',
                'label' => 'Needs Attention',
            ],
            $completionPercentage <= 70 => [
                'bar' => 'bg-yellow-500',
                'badge' => 'bg-yellow-100 text-yellow-700',
                'label' => 'In Progress',
            ],
            default => [
                'bar' => 'bg-green-500',
                'badge' => 'bg-green-100 text-green-700',
                'label' => 'Almost Ready',
            ],
        };

        $documentsPendingVerification = $this->hasPendingVerification($profile);

        return view('dashboards.guide', [
            'firstName' => $firstName,
            'completionPercentage' => $completionPercentage,
            'progressTheme' => $progressTheme,
            'criteria' => $criteria,
            'showCompletionReminder' => $completionPercentage < 100,
            'showVerificationNotice' => $documentsPendingVerification,
        ]);
    }

    public function admin(): View
    {
        $totalUsers = User::query()->count();
        $activeGuides = User::query()
            ->whereIn('role', ['guide', 'tour_guide'])
            ->where('status', 'active')
            ->count();

        $featuredTours = Schema::hasTable('tours') && Schema::hasColumn('tours', 'is_featured')
            ? Tour::query()->where('is_featured', true)->count()
            : 0;

        $pendingGuideApprovals = Schema::hasTable('tour_guides_profile') && Schema::hasColumn('tour_guides_profile', 'approved_by_admin')
            ? DB::table('tour_guides_profile')->where('approved_by_admin', false)->count()
            : 0;

        $recentUsers = User::query()
            ->select(['name', 'email', 'role', 'status', 'created_at'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboards.admin', [
            'stats' => [
                'total_users' => $totalUsers,
                'active_guides' => $activeGuides,
                'pending_guide_approvals' => $pendingGuideApprovals,
                'featured_tours' => $featuredTours,
            ],
            'recentUsers' => $recentUsers,
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

    private function firstName(string $name): string
    {
        $trimmed = trim($name);

        if ($trimmed === '') {
            return 'Guide';
        }

        return explode(' ', $trimmed)[0];
    }

    /**
     * @param  array<string, mixed>  $record
     * @param  array<int, string>  $keys
     */
    private function isFilled(array $record, array $keys): bool
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $record)) {
                continue;
            }

            $value = $record[$key];

            if (is_string($value) && trim($value) !== '') {
                return true;
            }

            if (is_numeric($value) && (string) $value !== '') {
                return true;
            }
        }

        return false;
    }

    private function hasAtLeastOneSelection(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_array($value)) {
            return count(array_filter($value, fn (mixed $item): bool => filled($item))) >= 1;
        }

        if (! is_string($value)) {
            return false;
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return count(array_filter($decoded, fn (mixed $item): bool => filled($item))) >= 1;
        }

        return collect(explode(',', $value))
            ->map(fn (string $item): string => trim($item))
            ->filter(fn (string $item): bool => $item !== '')
            ->isNotEmpty();
    }

    /**
     * @param  array<string, mixed>  $profile
     */
    private function hasPendingVerification(array $profile): bool
    {
        $pendingPairs = [
            ['path' => 'id_front_path', 'verified' => 'id_front_verified'],
            ['path' => 'id_back_path', 'verified' => 'id_back_verified'],
            ['path' => 'selfie_path', 'verified' => 'selfie_verified'],
            ['path' => 'nbi_clearance_path', 'verified' => 'nbi_clearance_validated'],
        ];

        foreach ($pendingPairs as $pair) {
            $pathValue = $profile[$pair['path']] ?? null;
            $verificationValue = $profile[$pair['verified']] ?? null;

            if (is_string($pathValue) && trim($pathValue) !== '' && ! (bool) $verificationValue) {
                return true;
            }
        }

        if (array_key_exists('approved_by_admin', $profile) && ! (bool) $profile['approved_by_admin']) {
            return $this->isFilled($profile, ['id_front_path', 'id_back_path', 'selfie_path', 'nbi_clearance_path', 'barangay_clearance_path']);
        }

        return false;
    }
}
