<?php

namespace App\Livewire\Guide;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\GuideStory;
use App\Models\Tour;
use App\Models\TourReview;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class GuideProfile extends Component
{
    use WithFileUploads;

    public string $postText = '';

    /**
     * @var array<int, TemporaryUploadedFile>
     */
    public array $postImages = [];

    /**
     * @var array<int, TemporaryUploadedFile>
     */
    public array $newPostImages = [];

    public ?int $editingPostId = null;

    public string $editingPostText = '';

    /**
     * @var array<int, string>
     */
    public array $messageInputs = [];

    /**
     * @var array<int, bool>
     */
    public array $messageComposerOpen = [];

    public function updatedNewPostImages(): void
    {
        $this->processNewPostImages();
    }

    public function render()
    {
        $user = $this->currentGuideUser();

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
        $tours = Tour::query()
            ->where('guide_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

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

    public function createPost(): void
    {
        $user = $this->currentGuideUser();

        $this->validate([
            'postText' => ['required', 'string', 'max:2000'],
            'postImages' => ['required', 'array', 'min:1', 'max:5'],
            'postImages.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $imagePaths = collect($this->postImages)
            ->map(fn (TemporaryUploadedFile $image): string => $image->store('guide/posts', 'public'))
            ->values()
            ->all();

        GuideStory::query()->create($this->existingGuideStoryColumns([
            'guide_id' => $user->id,
            'image_path' => $imagePaths[0] ?? '',
            'caption' => Str::limit($this->postText, 280, ''),
            'content' => $this->postText,
            'image_paths' => $imagePaths,
            'likes_count' => 0,
            'liked_by' => [],
            'messages' => [],
            'expires_at' => now()->addYears(10),
        ]));

        $this->reset(['postText', 'postImages']);
    }

    public function processNewPostImages(): void
    {
        $this->resetValidation('newPostImages');
        $this->resetValidation('newPostImages.*');
        $this->resetValidation('postImages');
        $this->resetValidation('postImages.*');

        $selectedPhotos = is_array($this->newPostImages)
            ? $this->newPostImages
            : [$this->newPostImages];

        $incomingPhotos = collect($selectedPhotos)
            ->filter(fn (mixed $photo): bool => $photo instanceof TemporaryUploadedFile || $photo instanceof UploadedFile)
            ->values();

        if ($incomingPhotos->isEmpty()) {
            return;
        }

        $this->postImages = collect($this->postImages)
            ->merge($incomingPhotos)
            ->filter(fn (mixed $photo): bool => $photo instanceof TemporaryUploadedFile || $photo instanceof UploadedFile)
            ->take(5)
            ->values()
            ->all();

        $this->validateOnly('postImages.*', [
            'postImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $this->reset('newPostImages');
    }

    public function cancelPostDraft(): void
    {
        $this->resetValidation('postText');
        $this->resetValidation('newPostImages');
        $this->resetValidation('newPostImages.*');
        $this->resetValidation('postImages');
        $this->resetValidation('postImages.*');
        $this->reset(['postText', 'postImages', 'newPostImages']);
    }

    public function removePostImage(int $index): void
    {
        if (! array_key_exists($index, $this->postImages)) {
            return;
        }

        unset($this->postImages[$index]);
        $this->postImages = array_values($this->postImages);
    }

    public function startEditingPost(int $postId): void
    {
        $user = $this->currentGuideUser();

        $post = GuideStory::query()
            ->whereKey($postId)
            ->where('guide_id', $user->id)
            ->firstOrFail();

        $this->editingPostId = $postId;
        $this->editingPostText = (string) ($post->content ?? $post->caption ?? '');
    }

    public function cancelEditingPost(): void
    {
        $this->reset(['editingPostId', 'editingPostText']);
    }

    public function updatePost(): void
    {
        $user = $this->currentGuideUser();

        if ($this->editingPostId === null) {
            return;
        }

        $this->validate([
            'editingPostText' => ['required', 'string', 'max:2000'],
        ]);

        $post = GuideStory::query()
            ->whereKey($this->editingPostId)
            ->where('guide_id', $user->id)
            ->firstOrFail();

        $post->forceFill($this->existingGuideStoryColumns([
            'content' => $this->editingPostText,
            'caption' => Str::limit($this->editingPostText, 280, ''),
        ]))->save();

        $this->reset(['editingPostId', 'editingPostText']);
    }

    public function deletePost(int $postId): void
    {
        $user = $this->currentGuideUser();

        $post = GuideStory::query()
            ->whereKey($postId)
            ->where('guide_id', $user->id)
            ->first();

        if ($post === null) {
            return;
        }

        $post->delete();

        if ($this->editingPostId === $postId) {
            $this->cancelEditingPost();
        }
    }

    public function toggleLike(int $postId): void
    {
        $user = $this->currentGuideUser();

        $post = GuideStory::query()->whereKey($postId)->firstOrFail();
        $likedBy = $this->normalizeArrayValue($post->liked_by);
        $currentUserId = (int) $user->id;

        if (in_array($currentUserId, $likedBy, true)) {
            $likedBy = array_values(array_filter($likedBy, fn (int $id): bool => $id !== $currentUserId));
        } else {
            $likedBy[] = $currentUserId;
        }

        $post->forceFill($this->existingGuideStoryColumns([
            'liked_by' => $likedBy,
            'likes_count' => count($likedBy),
        ]))->save();
    }

    public function toggleMessageComposer(int $postId): void
    {
        $this->messageComposerOpen[$postId] = ! ($this->messageComposerOpen[$postId] ?? false);
    }

    public function sendMessage(int $postId): void
    {
        $user = $this->currentGuideUser();

        $this->validate([
            'messageInputs.'.$postId => ['required', 'string', 'max:500'],
        ]);

        $post = GuideStory::query()->whereKey($postId)->firstOrFail();
        $messages = $this->normalizeArrayValue($post->messages);

        $messages[] = [
            'sender_id' => (int) $user->id,
            'sender_name' => (string) ($user->display_name ?? $user->full_name ?? $user->name),
            'content' => trim((string) ($this->messageInputs[$postId] ?? '')),
            'sent_at' => now()->toIso8601String(),
        ];

        $post->forceFill($this->existingGuideStoryColumns([
            'messages' => $messages,
        ]))->save();

        $this->messageInputs[$postId] = '';
        $this->messageComposerOpen[$postId] = false;
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

    private function currentGuideUser(): User
    {
        $user = Auth::user();
        abort_if($user === null, 401);
        abort_unless($user instanceof User, 403);
        abort_unless(in_array($user->role, ['guide', 'tour_guide'], true), 403);

        return $user;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function existingGuideStoryColumns(array $payload): array
    {
        $table = (new GuideStory)->getTable();

        return collect($payload)
            ->filter(fn (mixed $value, string $column): bool => Schema::hasColumn($table, $column))
            ->all();
    }

    /**
     * @return array<int, mixed>
     */
    private function normalizeArrayValue(mixed $value): array
    {
        if (is_array($value)) {
            return array_values($value);
        }

        if (is_string($value) && trim($value) !== '') {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return array_values($decoded);
            }
        }

        return [];
    }
}
