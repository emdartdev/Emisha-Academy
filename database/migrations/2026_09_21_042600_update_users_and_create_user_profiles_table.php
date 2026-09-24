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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bangladesh');
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('website_url')->nullable();
            $table->timestamps();
        });

        Schema::create('user_login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_type')->nullable();
            $table->timestamp('logged_in_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_histories');
        Schema::dropIfExists('user_profiles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'status', 'last_login_at', 'last_login_ip']);
        });
    }
};
