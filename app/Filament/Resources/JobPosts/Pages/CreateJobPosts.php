<?php

namespace App\Filament\Resources\JobPosts\Pages;

use App\Filament\Resources\JobPosts\JobPostsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJobPosts extends CreateRecord
{
    protected static string $resource = JobPostsResource::class;

    protected function handleRecordCreation($data): Model
    {
        dd($data);
        // $record = parent::handleRecordCreation($data);


        // $record->plantilla()->create([
        //     'job_post_id' => $record->id,
        //     'plantilla_no' => $data['plantilla_no'],
        //     'salary_grade' => $data['salary_grade'],
        //     'salary' => $data['monthly_salary'],
        //     'no_of_vacancies' => $data['no_of_vacancies'],
        // ]);

        // $record->qualifications()->create([
        //     'job_post_id' => $record->id,
        //     'educational_background' => $data['educational_background'],
        //     'qualification' => $data['qualification'],
        //     'experience' => $data['experience'],
        //     'competencies' => $data['competencies'],
        //     'trainings' => $data['trainings'],
        //     'additional_qualifications' => $data['additional_qualifications'],
        // ]);

        // foreach ($data['file_requirements'] as $key => $value) {
        //     $record->required_files()->create([
        //         'job_post_id' => $record->id,
        //         'file_type' => $key,
        //         'file_name' => $value,
        //         'is_required' => true,
        //     ]);
        // }


        // $record->status()->create([
        //     'job_post_id' => $record->id,
        //     'no_of_vacancies' => $data['no_of_vacancies'],
        //     'place_of_assignment' => $data['place_of_assignment'],
        //     'max_applicants' => $data['max_applicants'],
        //     'is_filled' => false,
        //     'is_active' => true,
        // ]);


        $record->published()->create([
            'job_post_id' => $record->id,
            'published_date' => $data['publication_date'],
            'closing_date' => $data['closing_date'],
            'closing_time' => $data['closing_time'],
            'max_applicants' => $data['max_applicants'],
        ]);



        return $record;
    }
}
