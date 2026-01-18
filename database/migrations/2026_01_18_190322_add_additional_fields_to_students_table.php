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
        Schema::table('students', function (Blueprint $table) {
            $table->string('email')->nullable()->after('student_id');
            $table->text('residential_address')->nullable()->after('address');
            $table->text('boarding_address')->nullable()->after('residential_address');
            $table->string('guardian_name')->nullable()->after('boarding_address');
            $table->string('guardian_contact')->nullable()->after('guardian_name');
            $table->string('mother_name')->nullable()->after('guardian_contact');
            $table->string('father_name')->nullable()->after('mother_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'residential_address',
                'boarding_address',
                'guardian_name',
                'guardian_contact',
                'mother_name',
                'father_name'
            ]);
        });
    }
};
