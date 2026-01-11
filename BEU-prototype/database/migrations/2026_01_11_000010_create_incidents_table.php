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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->dateTime('incident_date');
            $table->string('location');
            $table->text('description');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('violation_category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('violation_clause_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['reported', 'under_review', 'pending_approval', 'approved', 'closed'])->default('reported');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
