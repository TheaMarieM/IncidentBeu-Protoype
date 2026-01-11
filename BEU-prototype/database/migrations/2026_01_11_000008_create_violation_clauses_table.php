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
        Schema::create('violation_clauses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_category_id')->constrained()->onDelete('cascade');
            $table->string('clause_number'); // e.g., "Article III, Section 5.1"
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_clauses');
    }
};
