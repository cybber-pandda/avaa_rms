<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->string('interview_result')->nullable()->after('status'); // passed | failed
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->timestamp('hired_at')->nullable()->after('reviewed_at');
            $table->timestamp('contract_ended_at')->nullable()->after('hired_at');
        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn('interview_result');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['hired_at', 'contract_ended_at']);
        });
    }
};
