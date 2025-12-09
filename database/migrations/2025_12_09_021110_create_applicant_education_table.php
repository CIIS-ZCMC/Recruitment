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
        Schema::create('applicant_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->string("level")->nullable();
            $table->string("school")->nullable();
            $table->string("degree")->nullable();
            $table->string("year_from")->nullable();
            $table->string("year_to")->nullable();
            $table->string("units_earned")->nullable();
            $table->string("year_graduated")->nullable();
            $table->string("honor_received")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_education');
    }
};
