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
        Schema::table('webinars', function (Blueprint $table) {
            $table->json('highlights_bn')->nullable()->after('description_en');
            $table->json('highlights_en')->nullable()->after('highlights_bn');
            $table->json('agenda_bn')->nullable()->after('highlights_en');
            $table->json('agenda_en')->nullable()->after('agenda_bn');
            $table->string('certificate_title_bn')->nullable()->after('recording_url');
            $table->string('certificate_title_en')->nullable()->after('certificate_title_bn');
            $table->text('certificate_note_bn')->nullable()->after('certificate_title_en');
            $table->text('certificate_note_en')->nullable()->after('certificate_note_bn');
            $table->string('lab_upsell_title_bn')->nullable()->after('certificate_note_en');
            $table->string('lab_upsell_title_en')->nullable()->after('lab_upsell_title_bn');
            $table->text('lab_upsell_desc_bn')->nullable()->after('lab_upsell_title_en');
            $table->text('lab_upsell_desc_en')->nullable()->after('lab_upsell_desc_bn');
        });

        Schema::table('webinar_speakers', function (Blueprint $table) {
            $table->text('bio_bn')->nullable()->after('organization');
            $table->text('bio_en')->nullable()->after('bio_bn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            $table->dropColumn([
                'highlights_bn',
                'highlights_en',
                'agenda_bn',
                'agenda_en',
                'certificate_title_bn',
                'certificate_title_en',
                'certificate_note_bn',
                'certificate_note_en',
                'lab_upsell_title_bn',
                'lab_upsell_title_en',
                'lab_upsell_desc_bn',
                'lab_upsell_desc_en',
            ]);
        });

        Schema::table('webinar_speakers', function (Blueprint $table) {
            $table->dropColumn(['bio_bn', 'bio_en']);
        });
    }
};
