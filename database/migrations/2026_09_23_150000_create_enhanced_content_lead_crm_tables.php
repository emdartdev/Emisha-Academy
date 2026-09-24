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
        // 1. Upgrade leads table with content attribution & CRM pipeline fields
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'lead_type')) {
                $table->string('lead_type', 50)->default('general')->after('email');
            }
            if (!Schema::hasColumn('leads', 'source_content_type')) {
                $table->string('source_content_type', 50)->nullable()->after('lead_type');
            }
            if (!Schema::hasColumn('leads', 'source_content_id')) {
                $table->unsignedBigInteger('source_content_id')->nullable()->after('source_content_type');
            }
            if (!Schema::hasColumn('leads', 'source_content_title')) {
                $table->string('source_content_title', 255)->nullable()->after('source_content_id');
            }
            if (!Schema::hasColumn('leads', 'source_content_slug')) {
                $table->string('source_content_slug', 255)->nullable()->after('source_content_title');
            }
            if (!Schema::hasColumn('leads', 'source_url')) {
                $table->text('source_url')->nullable()->after('source_content_slug');
            }
            if (!Schema::hasColumn('leads', 'priority')) {
                $table->string('priority', 30)->default('normal')->after('status');
            }
            if (!Schema::hasColumn('leads', 'next_follow_up_at')) {
                $table->dateTime('next_follow_up_at')->nullable()->after('assigned_to');
            }
            if (!Schema::hasColumn('leads', 'last_contacted_at')) {
                $table->dateTime('last_contacted_at')->nullable()->after('next_follow_up_at');
            }
            if (!Schema::hasColumn('leads', 'utm_source')) {
                $table->string('utm_source', 100)->nullable()->after('last_contacted_at');
            }
            if (!Schema::hasColumn('leads', 'utm_medium')) {
                $table->string('utm_medium', 100)->nullable()->after('utm_source');
            }
            if (!Schema::hasColumn('leads', 'utm_campaign')) {
                $table->string('utm_campaign', 100)->nullable()->after('utm_medium');
            }
            if (!Schema::hasColumn('leads', 'utm_term')) {
                $table->string('utm_term', 100)->nullable()->after('utm_campaign');
            }
            if (!Schema::hasColumn('leads', 'utm_content')) {
                $table->string('utm_content', 100)->nullable()->after('utm_term');
            }
            if (!Schema::hasColumn('leads', 'referrer_url')) {
                $table->text('referrer_url')->nullable()->after('utm_content');
            }
        });

        // 2. Threaded Lead Notes Table
        if (!Schema::hasTable('lead_notes')) {
            Schema::create('lead_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('note');
                $table->timestamps();

                $table->index('lead_id');
                $table->index('user_id');
            });
        }

        // 3. Lead Activity Timeline Log Table
        if (!Schema::hasTable('lead_activities')) {
            Schema::create('lead_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action', 100);
                $table->text('description');
                $table->json('properties')->nullable();
                $table->timestamps();

                $table->index('lead_id');
                $table->index('action');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('lead_notes');

        Schema::table('leads', function (Blueprint $table) {
            $columnsToDrop = [
                'lead_type',
                'source_content_type',
                'source_content_id',
                'source_content_title',
                'source_content_slug',
                'source_url',
                'priority',
                'next_follow_up_at',
                'last_contacted_at',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'referrer_url',
            ];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('leads', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
