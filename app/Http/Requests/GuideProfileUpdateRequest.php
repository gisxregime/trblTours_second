<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuideProfileUpdateRequest extends FormRequest
{
    /**
     * @var array<int, string>
     */
    private const REGIONS = [
        'National Capital Region',
        'Cordillera Administrative Region',
        'Ilocos Region',
        'Cagayan Valley',
        'Central Luzon',
        'Calabarzon',
        'Mimaropa',
        'Bicol Region',
        'Western Visayas',
        'Central Visayas',
        'Eastern Visayas',
        'Zamboanga Peninsula',
        'Northern Mindanao',
        'Davao Region',
        'Soccsksargen',
        'Caraga',
        'Bangsamoro Autonomous Region in Muslim Mindanao',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && in_array($this->user()->role, ['guide', 'tour_guide'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'display_name' => ['required', 'string', 'max:120'],
            'phone_number' => ['required', 'string', 'regex:/^09\d{2}-\d{3}-\d{4}$/'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->format('Y-m-d')],
            'region' => ['required', 'string', Rule::in(self::REGIONS)],
            'city_municipality' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'min:100', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_number.regex' => 'Phone number must be in the format 09XX-XXX-XXXX.',
            'date_of_birth.before_or_equal' => 'You must be at least 18 years old.',
            'bio.min' => 'Bio must be at least 100 characters.',
            'bio.max' => 'Bio must not exceed 1000 characters.',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function regions(): array
    {
        return self::REGIONS;
    }
}
