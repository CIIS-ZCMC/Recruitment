<?php

namespace App\Filament\Resources\JobPosts\Pages;

use App\Filament\Resources\JobPosts\JobPostsResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJobPosts extends CreateRecord
{
    protected static string $resource = JobPostsResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function handleRecordCreation($data): Model
    {


        $record = parent::handleRecordCreation($data);

        if (isset($data['plantilla'])) {
            foreach ($data['plantilla'] as $plantilla) {
                $record->plantilla()->create([
                    'job_post_id' => $record->id,
                    'plantilla_no' => $plantilla['plantilla'],
                    'salary_grade' => $data['salary_grade'],
                    'salary' => $data['salary'],
                ]);
            }
        } else {
            $jobTitle = $data['title'];
            for ($i = 1; $i <= $data['no_of_vacancies']; $i++) {
                $record->plantilla()->create([
                    'job_post_id' => $record->id,
                    'plantilla_no' => "JOB-ORDER-$jobTitle-$i",
                    'salary_grade' => $data['salary_grade'],
                    'salary' => $data['salary'],
                    'is_contract' => true
                ]);
            }
        }

        $record->qualifications()->create([
            'job_post_id' => $record->id,
            'educational_background' => $data['educational_background'],
            'qualification' => $data['qualification'],
            'experience' => $data['experience'],
            'competencies' => $data['competencies'],
            'trainings' => $data['trainings'],
            'additional_qualifications' => isset($data['additional_qualifications']) ? $data['additional_qualifications'] : null,
        ]);

        foreach ($data['file_requirements'] as $file) {
            $record->required_files()->create([
                'job_post_id' => $record->id,
                'file_type' => $file['file_type'],
                'file_name' => $file['file_name'],
                'is_required' => true,
            ]);
        }


        $record->status()->create([
            'job_post_id' => $record->id,
            'place_of_assignment' => $data['place_of_assignment'],
            'is_filled' => false,
            'is_active' => true,
        ]);

        $record->published()->create([
            'job_post_id' => $record->id,
            'published_date' => $data['publication_date'],
            'closing_date' => isset($data['closing_date']) ? date("Y-m-d", strtotime($data['closing_date'])) : null,
            'closing_time' => isset($data['closing_date']) ? date("H:i:s", strtotime($data['closing_date'])) : null,
            'max_applicants' => $data['max_applicants'],
        ]);

        Notification::make()
            ->success()
            ->color('success')
            ->title('Job Post Created')
            ->body('The job post has been created successfully.')
            ->send();



        return $record;
    }
}
