<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantStore;
use App\Models\Applicant;
use App\Models\ApplicantEducation;
use App\Models\ApplicantEligibility;
use App\Models\ApplicantExperiences;
use App\Models\ApplicantInformation;
use App\Models\ApplicantReferences;
use App\Models\ApplicantTraining;
use App\Models\ApplicantVoluntaryWork;
use Illuminate\Http\Request;


class ApplicantController extends Controller
{
    public function store(ApplicantStore $request)
    {
        $applicants = array_merge($request->only($request->Applicant()), [
            'name' => $request->ApplicantFullName(),
            'password' => $request->HashedPassword(),
        ]);
        $PersonalInformation = $request->only($request->PersonalInformations());
        $applicant = Applicant::firstOrCreate($applicants);
        ApplicantInformation::firstOrCreate([
            'applicant_id' => $applicant->id,
            ...$PersonalInformation
        ]);
        if (!empty($request->Educations())) {
            foreach ($request->Educations() as $data) {
                ApplicantEducation::firstOrCreate([
                    'applicant_id' => $applicant->id,
                    ...$data
                ]);
            }
        }
        if (!empty($request->Eligibilities())) {
            foreach ($request->Eligibilities() as $data) {
                ApplicantEligibility::firstOrCreate([
                    'applicant_id' => $applicant->id,
                    ...$data
                ]);
            }
        }
        if (!empty($request->Experiences())) {
            foreach ($request->Experiences() as $data) {
                ApplicantExperiences::firstOrCreate([
                    'applicant_id' => $applicant->id,
                    ...$data
                ]);
            }
        }
        if (!empty($request->Trainings())) {
            foreach ($request->Trainings() as $data) {
                ApplicantTraining::firstOrCreate([
                    'applicant_id' => $applicant->id,
                    ...$data
                ]);
            }
        }
        if (!empty($request->VoluntaryWorks())) {
            foreach ($request->VoluntaryWorks() as $data) {
                ApplicantVoluntaryWork::firstOrCreate([
                    'applicant_id' => $applicant->id,
                    ...$data
                ]);
            }
        }
        if (!empty($request->References())) {
            foreach ($request->References() as $data) {
                ApplicantReferences::firstOrCreate([
                    'applicant_id' => $applicant->id,
                    ...$data
                ]);
            }
        }

        return response()->json([
            'message' => 'Applicant created successfully',
            'data' => $applicant
        ], 201);
    }
}
