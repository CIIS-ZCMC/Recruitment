<?php

namespace App\Filament\Resources\JobPosts\Pages;

use App\Filament\Resources\JobPosts\JobPostsResource;
use App\Models\JobPostFiles;
use App\Models\JobPostPlantilla;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditJobPosts extends EditRecord
{
    protected static string $resource = JobPostsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    public function handleRecordUpdate(Model $record, array $data): Model
    {


        // dd($data);
        $record->update($data);

        if (isset($data['plantilla'])) {

            $updateData = [];

            //   dd($data['plantilla']);
            foreach ($data['plantilla'] as $plantilla) {
                $updateData[] = $plantilla['plantilla'];
                if (isset($plantilla['id'])) {
                    $record->plantilla()->where('id', $plantilla['id'])->update([
                        'job_post_id' => $record->id,
                        'plantilla_no' => $plantilla['plantilla'],
                        'salary_grade' => $data['salary_grade'],
                        'salary' => $data['salary'],
                    ]);
                } else {
                    JobPostPlantilla::create([
                        'job_post_id' => $record->id,
                        'plantilla_no' => $plantilla['plantilla'],
                        'salary_grade' => $data['salary_grade'],
                        'salary' => $data['salary'],
                    ]);
                }
            }
            //    dd($updateData);
            $record->plantilla()->whereNotIn('plantilla_no', $updateData)->delete();
        } else {
            $jobTitle = $data['title'];
            $updateData = [];
            for ($i = 1; $i <= $data['no_of_vacancies']; $i++) {

                $updateData[] = "JOB-ORDER-$jobTitle-$i";

                $record->plantilla()->updateOrCreate(
                    [
                        'job_post_id' => $record->id,
                        'plantilla_no' => "JOB-ORDER-$jobTitle-$i",
                    ],
                    [
                        'salary_grade' => $data['salary_grade'],
                        'salary' => $data['salary'],
                        'is_contract' => true
                    ]
                );
            }
            $record->plantilla()->whereNotIn('plantilla_no', $updateData)->delete();
        }



        $record->qualifications()->updateOrCreate(
            ['job_post_id' => $record->id],
            [
                'educational_background' => $data['educational_background'],
                'qualification' => $data['qualification'],
                'experience' => $data['experience'],
                'competencies' => $data['competencies'],
                'skills' => $data['skills'],
                'trainings' => $data['trainings'],
                'additional_qualifications' => isset($data['additional_qualifications']) ? $data['additional_qualifications'] : null,
            ]
        );

        $updateDatafiles = [];
        foreach ($data['file_requirements'] as $file) {
            $updateDatafiles[] = $file['file_name'];

            if (isset($file['id'])) {
                $record->required_files()->where('id', $file['id'])->update(
                    [
                        'job_post_id' => $record->id,
                        'file_type' => $file['file_type'],
                        'file_name' => $file['file_name'],
                        'is_required' => true,
                    ]
                );
            } else {
                JobPostFiles::create(
                    [
                        'job_post_id' => $record->id,
                        'file_type' => $file['file_type'],
                        'file_name' => $file['file_name'],
                        'is_required' => true,
                    ]
                );
            }
        }
        $record->required_files()->whereNotIn('file_name', $updateDatafiles)->delete();


        $record->status()->updateOrCreate([
            'job_post_id' => $record->id,
            'place_of_assignment' => $data['place_of_assignment'],
            'is_filled' => false,
            'is_active' => true,
        ]);

        $record->published()->Update([
            'job_post_id' => $record->id,
            'published_date' => $data['publication_date'],
            'closing_date' => isset($data['closing_date']) ? date("Y-m-d", strtotime($data['closing_date'])) : null,
            'closing_time' => isset($data['closing_date']) ? date("H:i:s", strtotime($data['closing_date'])) : null,
            'max_applicants' => $data['max_applicants'],
        ]);


        Notification::make()
            ->success()
            ->color('success')
            ->title('Job Post Updated')
            ->body('The job post has been updated successfully.')
            ->send();

        return $record;
    }
}
