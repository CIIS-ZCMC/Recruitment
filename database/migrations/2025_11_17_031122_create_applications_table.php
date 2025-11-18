<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicants');
            $table->unsignedBigInteger('published_job_post_id');
            $table->foreign('published_job_post_id')->references('id')->on('published_job_posts');
            $table->enum(
                "status",
                [
                    "pending",
                    "approved",
                    "shortlisted",
                    "interviewed",
                    "recommended",
                    "hired",
                    "onboarded",
                    //Negative-----------
                    "failed_interview",
                    "not_qualified",
                    "rejected",
                    "not_selected",
                    "withdrawn",
                    "no_show",
                ]
            )->default("pending");
            $table->longText("remarks")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
