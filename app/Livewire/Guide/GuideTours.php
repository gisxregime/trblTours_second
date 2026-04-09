<?php

namespace App\Livewire\Guide;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GuideTours extends Component
{
    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    public ?int $editingTourId = null;

    public function mount(): void
    {
        $this->resetForm();
    }

    public function save(): void
    {
        $guideId = $this->guideId();

        $validated = $this->validate($this->rules());

        $tour = $this->editingTourId !== null
            ? $this->getGuideToursQuery($guideId)->whereKey($this->editingTourId)->firstOrFail()
            : new Tour;

        $payload = $this->existingColumns($this->payloadForTour($validated['form'], $guideId));
        $tour->forceFill($payload)->save();

        $this->dispatch('tour-saved');
        $this->resetEditingState();
    }

    public function edit(int $tourId): void
    {
        $tour = $this->getGuideToursQuery($this->guideId())->whereKey($tourId)->firstOrFail();

        $this->editingTourId = (int) $tour->id;
        $this->form = $this->tourToForm($tour);
    }

    public function delete(int $tourId): void
    {
        $this->getGuideToursQuery($this->guideId())->whereKey($tourId)->delete();
        if ($this->editingTourId === $tourId) {
            $this->resetEditingState();
        }
    }

    public function cancelEdit(): void
    {
        $this->resetEditingState();
    }

    public function render()
    {
        $guideId = $this->guideId();

        $tours = $this->getGuideToursQuery($guideId)
            ->latest()
            ->get();

        return view('livewire.guide.guide-tours', [
            'tours' => $tours,
            'title' => 'Guide Tours',
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function rules(): array
    {
        return [
            'form.title' => ['required', 'string', 'max:255'],
            'form.region' => ['required', 'string', 'max:255'],
            'form.city' => ['nullable', 'string', 'max:255'],
            'form.category' => ['nullable', 'string', 'max:255'],
            'form.summary' => ['required', 'string', 'max:1000'],
            'form.duration_label' => ['required', 'string', 'max:255'],
            'form.duration_hours' => ['nullable', 'integer', 'min:1', 'max:168'],
            'form.price_per_person' => ['required', 'numeric', 'min:0'],
            'form.available_on' => ['nullable', 'date'],
            'form.is_featured' => ['boolean'],
            'form.status' => ['required', 'string', 'in:draft,pending_review,active,paused'],
        ];
    }

    private function guideId(): int
    {
        return (int) Auth::id();
    }

    /**
     * @return Builder<Tour>
     */
    private function getGuideToursQuery(int $guideId)
    {
        return Tour::query()
            ->where(function ($query) use ($guideId): void {
                if (Schema::hasColumn('tours', 'guide_id')) {
                    $query->where('guide_id', $guideId)
                        ->orWhere(function ($fallback) use ($guideId): void {
                            $fallback->whereNull('guide_id')->where('created_by', $guideId);
                        });
                } else {
                    $query->where('created_by', $guideId);
                }
            });
    }

    /**
     * @param  array<string, mixed>  $form
     * @return array<string, mixed>
     */
    private function payloadForTour(array $form, int $guideId): array
    {
        $payload = [
            'guide_id' => $guideId,
            'created_by' => $guideId,
            'title' => $form['title'],
            'name' => $form['title'],
            'region' => $form['region'],
            'city' => $form['city'] ?? null,
            'category' => $form['category'] ?? null,
            'summary' => $form['summary'],
            'description' => $form['summary'],
            'duration_label' => $form['duration_label'],
            'duration_hours' => $form['duration_hours'] !== '' ? (int) $form['duration_hours'] : null,
            'price_per_person' => (float) $form['price_per_person'],
            'price' => (float) $form['price_per_person'],
            'available_on' => $form['available_on'] !== '' ? $form['available_on'] : null,
            'is_featured' => (bool) ($form['is_featured'] ?? false),
            'status' => $form['status'],
        ];

        if (Schema::hasColumn('tours', 'short_description')) {
            $payload['short_description'] = $form['summary'];
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    private function tourToForm(Tour $tour): array
    {
        return [
            'title' => (string) ($tour->title ?? $tour->name ?? ''),
            'region' => (string) $tour->region,
            'city' => (string) ($tour->city ?? ''),
            'category' => (string) ($tour->category ?? ''),
            'summary' => (string) ($tour->summary ?? $tour->description ?? ''),
            'duration_label' => (string) ($tour->duration_label ?? $tour->duration ?? ''),
            'duration_hours' => (string) ($tour->duration_hours ?? ''),
            'price_per_person' => (string) ($tour->price_per_person ?? $tour->price ?? ''),
            'available_on' => (string) ($tour->available_on ?? ''),
            'is_featured' => (bool) $tour->is_featured,
            'status' => (string) ($tour->status ?? 'draft'),
        ];
    }

    private function resetForm(): void
    {
        $this->form = [
            'title' => '',
            'region' => '',
            'city' => '',
            'category' => '',
            'summary' => '',
            'duration_label' => '',
            'duration_hours' => '',
            'price_per_person' => '',
            'available_on' => '',
            'is_featured' => false,
            'status' => 'draft',
        ];
    }

    private function resetEditingState(): void
    {
        $this->editingTourId = null;
        $this->resetForm();
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function existingColumns(array $payload): array
    {
        return collect($payload)
            ->filter(function (mixed $value, string $column): bool {
                return Schema::hasColumn('tours', $column);
            })
            ->all();
    }
}
