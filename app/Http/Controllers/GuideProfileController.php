<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuideProfileUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class GuideProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        $profile = $this->getGuideProfile((int) $user->id);

        return view('dashboards.guide-profile-show', [
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
        ]);
    }

    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        $profile = $this->getGuideProfile((int) $user->id);

        return view('dashboards.guide-profile-edit', [
            'regions' => GuideProfileUpdateRequest::regions(),
            'form' => [
                'full_name' => $user->name,
                'display_name' => $user->display_name ?? $user->full_name ?? $user->name,
                'phone_number' => (string) ($profile['phone_number'] ?? ''),
                'date_of_birth' => (string) ($profile['date_of_birth'] ?? ''),
                'region' => $user->region,
                'city_municipality' => $this->firstFilledValue($profile, ['city_municipality', 'city', 'municipality']),
                'barangay' => $this->firstFilledValue($profile, ['barangay', 'barangay_name']),
                'bio' => $this->firstFilledValue($profile, ['bio'], (string) ($user->bio ?? '')),
                'profile_photo_path' => $this->firstFilledValue($profile, ['profile_photo_path', 'profile_picture', 'avatar_path']),
                'cover_photo_path' => $this->firstFilledValue($profile, ['cover_photo_path', 'cover_image_path']),
            ],
        ]);
    }

    public function update(GuideProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $existingProfile = $this->getGuideProfile((int) $user->id);

        $profilePhotoPath = $request->file('profile_photo')?->store('guide/profile-photos', 'public');
        $coverPhotoPath = $request->file('cover_photo')?->store('guide/cover-photos', 'public');

        if ($profilePhotoPath !== null && ! empty($existingProfile['profile_photo_path'])) {
            Storage::disk('public')->delete((string) $existingProfile['profile_photo_path']);
        }

        if ($coverPhotoPath !== null && ! empty($existingProfile['cover_photo_path'])) {
            Storage::disk('public')->delete((string) $existingProfile['cover_photo_path']);
        }

        DB::transaction(function () use ($user, $validated, $profilePhotoPath, $coverPhotoPath, $existingProfile): void {
            $userData = [
                'name' => $validated['full_name'],
            ];

            if (Schema::hasColumn('users', 'full_name')) {
                $userData['full_name'] = $validated['full_name'];
            }

            if (Schema::hasColumn('users', 'display_name')) {
                $userData['display_name'] = $validated['display_name'];
            }

            if (Schema::hasColumn('users', 'region')) {
                $userData['region'] = $validated['region'];
            }

            if (Schema::hasColumn('users', 'bio')) {
                $userData['bio'] = $validated['bio'];
            }

            $user->forceFill($userData)->save();

            if (Schema::hasTable('tour_guides_profile')) {
                $profileColumns = array_flip(Schema::getColumnListing('tour_guides_profile'));

                $profileData = array_intersect_key([
                    'phone_number' => $validated['phone_number'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'city_municipality' => $validated['city_municipality'],
                    'barangay' => $validated['barangay'],
                    'bio' => $validated['bio'],
                    'profile_photo_path' => $profilePhotoPath !== null ? $profilePhotoPath : ($existingProfile['profile_photo_path'] ?? null),
                    'updated_at' => now(),
                ], $profileColumns);

                if ($coverPhotoPath !== null && isset($profileColumns['cover_photo_path'])) {
                    $profileData['cover_photo_path'] = $coverPhotoPath;
                }

                $existingProfileRecord = DB::table('tour_guides_profile')->where('user_id', $user->id)->first();

                if ($existingProfileRecord === null) {
                    $defaultProfileData = array_intersect_key([
                        'user_id' => $user->id,
                        'nationality' => 'Filipino',
                        'years_of_experience' => 1,
                        'government_id_type' => 'national_id',
                        'government_id_number' => '-',
                        'nbi_clearance_number' => '-',
                        'created_at' => now(),
                    ], $profileColumns);

                    DB::table('tour_guides_profile')->insert(array_merge($defaultProfileData, $profileData));
                } else {
                    DB::table('tour_guides_profile')
                        ->where('user_id', $user->id)
                        ->update($profileData);
                }
            }
        });

        return response()->json([
            'message' => 'Guide profile updated successfully.',
            'redirect_to' => route('dashboard.guide.profile.show'),
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
