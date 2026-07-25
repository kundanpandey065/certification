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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('sl_no')->nullable();
            $table->string('state_ut')->nullable();
            $table->string('district')->nullable();
            $table->string('school_name')->nullable();
            $table->string('candidate_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('class_standard')->nullable();
            $table->string('aadhaar_number')->nullable();
            $table->string('school_code')->nullable();
            $table->string('alternate_id')->nullable();
            $table->string('father_name')->nullable();
            $table->string('ssc_name')->nullable();
            $table->string('job_role')->nullable();
            $table->string('level')->nullable();
            $table->string('pass_fail')->nullable();
            $table->string('training_type')->nullable();
            $table->string('candidate_organization')->nullable();
            $table->string('candidate_id')->nullable();
            $table->string('certificate_no')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->date('date_of_issue')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
