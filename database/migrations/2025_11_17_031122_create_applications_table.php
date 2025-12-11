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
            $table->enum("status", [

                // Pre-Screening
                "pending",
                "for_initial_screening",
                "for_shortlisting",

                // Shortlisting & Evaluation
                "shortlisted",
                "for_interview",
                "interviewed",
                "for_assessment",
                "assessment_completed",

                // Background Check & Recommendation
                "for_background_check",
                "recommended",
                "for_final_deliberation",
                "approved_for_hire",

                // Hiring & Onboarding
                "job_offer_sent",
                "job_offer_accepted",
                "pre_employment_requirements",
                "hired",
                "onboarded",

                // Negative results
                "not_qualified",
                "failed_assessment",
                "failed_interview",
                "not_selected",
                "rejected",
                "withdrawn",
                "no_show",
                "offer_declined",

            ])->default("pending");

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
