<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // ── Bio ──
            $table->text('about')->nullable();

            // ── Professional info ──
            $table->string('professional_title')->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->string('years_of_experience')->nullable();

            // ── Location ──
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            // ── Skills & qualifications ──
            $table->json('skills')->nullable();
            $table->json('certifications')->nullable();
            $table->string('highest_education')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('institution_name')->nullable();

            // ── Resume ──
            $table->string('resume_path')->nullable();
            $table->json('resume_parsed_data')->nullable();

            // ── Links ──
            $table->string('portfolio_url')->nullable();
            $table->string('linkedin_url')->nullable();

            // ── Job preferences ──
            $table->json('employment_type_preference')->nullable();
            $table->json('desired_job_types')->nullable();
            $table->json('desired_industries')->nullable();
            $table->decimal('expected_salary_min', 10, 2)->nullable();
            $table->decimal('expected_salary_max', 10, 2)->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->boolean('willing_to_relocate')->default(false);

            // ── Availability ──
            $table->string('notice_period')->nullable();
            $table->string('work_style')->nullable();
            $table->string('weekly_hours')->nullable();

            // ── Privacy & completeness ──
            $table->enum('profile_visibility', ['public', 'private'])->default('public');
            $table->integer('profile_completeness')->default(0);

            $table->timestamps();

            $table->unique('user_id');
            $table->index(['city', 'country']);
            $table->index('years_of_experience');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_seeker_profiles');
    }
};