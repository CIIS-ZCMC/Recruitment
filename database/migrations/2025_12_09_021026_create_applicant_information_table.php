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
        Schema::create('applicant_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->string("last_name");
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("civil_status");
            $table->string("blood_type");
            $table->string("religion");
            $table->string("citizenship");
            $table->string("sex");
            $table->string("place_of_birth");
            $table->date("date_of_birth");
            $table->text("resident_address");
            $table->string("residential_phone");
            $table->string("residential_zipcode");
            $table->text("permanent_address");
            $table->string("permanent_phone");
            $table->string("permanent_zipcode");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_information');
    }
};
