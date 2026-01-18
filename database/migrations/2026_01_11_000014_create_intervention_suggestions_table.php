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
        Schema::create('intervention_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('grade_level')->nullable();
            $table->string('section')->nullable();
            $table->string('incident_type');
            $table->integer('incident_count');
            $table->date('analysis_period_start');
            $table->date('analysis_period_end');
            $table->text('suggestion');
            $table->enum('status', ['pending', 'approved', 'rejected', 'implemented'])->default('pending');
            $table->foreignId('decided_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('decided_at')->nullable();
            $table->text('decision_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention_suggestions');
    }
};
