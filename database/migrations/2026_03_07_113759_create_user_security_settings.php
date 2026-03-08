<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_security_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->boolean('two_factor_enabled')->default(false);
            $table->boolean('login_alert_email')->default(true);
            $table->boolean('login_alert_push')->default(true);
            $table->enum('marketplace_visibility', ['public', 'agency_only', 'private'])->default('public');
            $table->boolean('show_contact_info')->default(true);
            $table->boolean('show_ratings')->default(true);
            $table->boolean('hide_while_employed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_security_settings');
    }
};