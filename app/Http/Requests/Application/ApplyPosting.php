<?php

namespace App\Http\Requests\Application;

use App\Models\Applicant;
use App\Models\Applications;
use App\Models\JobPosts;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ApplyPosting extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'published_job_post_id' => 'required|exists:job_posts,id',
        ];
    }

    public function isAllowedToApply()
    {

        $jobpost = JobPosts::find($this->published_job_post_id);
        $published = $jobpost->published;
        $today = Carbon::now();
        $maxApplicants = $published->max_applicants;
        $datePublished = Carbon::parse($published->published_date)->startOfDay();
        $closingDateTime = Carbon::parse($published->closing_date . " " . $published->closing_time);
        $applicants = Applications::where("published_job_post_id", $this->published_job_post_id)->count();

        //Check count of application and max applicants
        if ($maxApplicants && $applicants >= $maxApplicants) {
            return ['allowed' => false, 'reason' => 'Maximum applicants reached'];
        }

        // check if today date and time ~ is within published date and closing datetime
        if (!$today->between($datePublished, $closingDateTime, true)) {
            return ['allowed' => false, 'reason' => 'Application period has ended'];
        }

        //Check if there's hired application. then count and compare to the vacant position.
        $hiredApplications = Applications::where("published_job_post_id", $this->published_job_post_id)
            ->whereIn("status", ["hired", "onboarded"])
            ->count();
        $vacantPositions = $jobpost->plantilla->count();
        if ($hiredApplications >= $vacantPositions) {
            return ['allowed' => false, 'reason' => 'All positions are filled'];
        }

        return [
            'allowed' => true,
        ];
    }

    public function checkIfAlreadyApplied($applicantID)
    {
        $applicant = Applicant::where('id', $applicantID)->first();
        if (!$applicant) {
            return false;
        }
        $exists = Applications::where('applicant_id', $applicant->id)
            ->where('published_job_post_id', $this->published_job_post_id)
            ->exists();

        return $exists;
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($this->validator, response()->json([
            'message' => "Something went wrong.\n System validation failed",
            'errors' => $this->validator->errors(),
        ], 422));
    }
}
