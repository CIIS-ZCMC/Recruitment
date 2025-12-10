<?php

namespace App\Http\Controllers;

use App\Http\Requests\Application\ApplyPosting;
use App\Models\Applications;
use Illuminate\Http\Request;
use League\Uri\Http;

class ApplicationController extends Controller
{
    public function ApplyPosting(ApplyPosting $request)
    {
        $jobpostID = $request->published_job_post_id;
        $applicant_id = 1;
        $permission = $request->isAllowedToApply();

        if (!$permission['allowed']) {
            return response()->json([
                'message' => $permission['reason']
            ], 500);
        }

        if ($request->checkIfAlreadyApplied($applicant_id)) {
            return response()->json([
                'message' => "Application already submitted for this position"
            ], 403);
        }

        Applications::create([
            'published_job_post_id' => $jobpostID,
            'applicant_id' => $applicant_id,
        ]);

        return response()->json([
            'message' => 'Application submitted successfully'
        ]);
    }
}
