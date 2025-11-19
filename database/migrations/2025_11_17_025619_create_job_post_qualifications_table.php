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
        Schema::create('job_post_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_post_id');
            $table->foreign('job_post_id')->references('id')->on('job_posts');
            $table->longText("educational_background")->nullable();
            $table->longText("qualification")->nullable();
            $table->longText("experience")->nullable();
            $table->longText("competencies")->nullable();
            $table->longText("skills")->nullable();
            $table->longText("trainings")->nullable();
            $table->longText("additional_qualifications")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_post_qualifications');
    }
};
