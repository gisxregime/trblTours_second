<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('tours')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table): void {
            if (! Schema::hasColumn('tours', 'guide_id')) {
                $table->foreignId('guide_id')->nullable()->constrained('users')->nullOnDelete()->index();
            }

            if (! Schema::hasColumn('tours', 'title')) {
                $table->string('title')->nullable()->after('guide_id');
            }

            if (! Schema::hasColumn('tours', 'city')) {
                $table->string('city')->nullable()->after('region');
            }

            if (! Schema::hasColumn('tours', 'category')) {
                $table->string('category')->nullable()->after('city');
            }

            if (! Schema::hasColumn('tours', 'duration_hours')) {
                $table->unsignedInteger('duration_hours')->nullable()->after('category');
            }

            if (! Schema::hasColumn('tours', 'duration_unit')) {
                $table->enum('duration_unit', ['hours', 'days'])->default('hours')->after('duration_hours');
            }

            if (! Schema::hasColumn('tours', 'min_guests')) {
                $table->unsignedInteger('min_guests')->nullable()->after('duration_unit');
            }

            if (! Schema::hasColumn('tours', 'max_guests')) {
                $table->unsignedInteger('max_guests')->nullable()->after('min_guests');
            }

            if (! Schema::hasColumn('tours', 'difficulty')) {
                $table->enum('difficulty', ['easy', 'moderate', 'challenging'])->nullable()->after('max_guests');
            }

            if (! Schema::hasColumn('tours', 'short_description')) {
                $table->string('short_description')->nullable()->after('difficulty');
            }

            if (! Schema::hasColumn('tours', 'full_itinerary')) {
                $table->text('full_itinerary')->nullable()->after('short_description');
            }

            if (! Schema::hasColumn('tours', 'inclusions')) {
                $table->json('inclusions')->nullable()->after('full_itinerary');
            }

            if (! Schema::hasColumn('tours', 'exclusions')) {
                $table->text('exclusions')->nullable()->after('inclusions');
            }

            if (! Schema::hasColumn('tours', 'what_to_bring')) {
                $table->text('what_to_bring')->nullable()->after('exclusions');
            }

            if (! Schema::hasColumn('tours', 'base_price')) {
                $table->decimal('base_price', 10, 2)->nullable()->after('what_to_bring');
            }

            if (! Schema::hasColumn('tours', 'pricing_tiers')) {
                $table->json('pricing_tiers')->nullable()->after('base_price');
            }

            if (! Schema::hasColumn('tours', 'fiesta_surcharge_enabled')) {
                $table->boolean('fiesta_surcharge_enabled')->default(false)->after('pricing_tiers');
            }

            if (! Schema::hasColumn('tours', 'fiesta_surcharge_amount')) {
                $table->decimal('fiesta_surcharge_amount', 10, 2)->nullable()->after('fiesta_surcharge_enabled');
            }

            if (! Schema::hasColumn('tours', 'fiesta_start_date')) {
                $table->date('fiesta_start_date')->nullable()->after('fiesta_surcharge_amount');
            }

            if (! Schema::hasColumn('tours', 'fiesta_end_date')) {
                $table->date('fiesta_end_date')->nullable()->after('fiesta_start_date');
            }

            if (! Schema::hasColumn('tours', 'blackout_dates')) {
                $table->json('blackout_dates')->nullable()->after('fiesta_end_date');
            }

            if (! Schema::hasColumn('tours', 'featured_image')) {
                $table->string('featured_image')->nullable()->after('blackout_dates');
            }

            if (! Schema::hasColumn('tours', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('featured_image');
            }

            if (! Schema::hasColumn('tours', 'status')) {
                $table->enum('status', ['draft', 'pending_review', 'active', 'paused'])->default('draft')->after('gallery_images');
            }

            if (! Schema::hasColumn('tours', 'admin_approved')) {
                $table->boolean('admin_approved')->default(false)->after('status');
            }

            $table->index(['guide_id', 'status']);
            $table->index(['guide_id', 'admin_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('tours')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table): void {
            $columns = [
                'title',
                'city',
                'category',
                'duration_hours',
                'duration_unit',
                'min_guests',
                'max_guests',
                'difficulty',
                'short_description',
                'full_itinerary',
                'inclusions',
                'exclusions',
                'what_to_bring',
                'base_price',
                'pricing_tiers',
                'fiesta_surcharge_enabled',
                'fiesta_surcharge_amount',
                'fiesta_start_date',
                'fiesta_end_date',
                'blackout_dates',
                'featured_image',
                'gallery_images',
                'status',
                'admin_approved',
            ];

            $existingColumns = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tours', $column)));
            if ($existingColumns !== []) {
                $table->dropColumn($existingColumns);
            }

            if (Schema::hasColumn('tours', 'guide_id')) {
                $table->dropConstrainedForeignId('guide_id');
            }
        });
    }
};
