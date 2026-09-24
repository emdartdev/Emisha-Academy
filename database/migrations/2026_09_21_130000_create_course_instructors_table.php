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
        Schema::create('course_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('instructors')->cascadeOnDelete();
            $table->string('role_bn')->nullable(); // e.g. "প্রধান প্রশিক্ষক (Lead Trainer)", "ভিসা স্পেশালিস্ট"
            $table->string('role_en')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->unique(['course_id', 'instructor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_instructors');
    }
};
