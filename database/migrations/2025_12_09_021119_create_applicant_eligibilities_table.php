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
        Schema::create('applicant_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->string("eligibility")->nullable();
            $table->string("rating")->nullable();
            $table->date("date_of_exam")->nullable();
            $table->text("place_of_examination")->nullable();
            $table->string("license_no")->nullable();
            $table->date("released_date")->nullable();
            $table->date("date_of_validity")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_eligibilities');
    }
};
