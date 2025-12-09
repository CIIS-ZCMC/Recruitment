<?php

namespace App\Http\Requests;

use App\Models\ApplicantInformation;
use App\Models\Applicant;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class ApplicantStore extends FormRequest
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
            'email' => 'required|email|max:255|unique:Applicants,email',
            'phone' => 'required|string|max:20',
            'status' => 'required|string',
            'password' => 'required|string',

            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',

            'civil_status' => 'required|string',
            'blood_type' => 'nullable|string|max:5',
            'religion' => 'nullable|string|max:255',
            'citizenship' => 'required|string',
            'sex' => 'required|in:Male,Female',

            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',

            'resident_address' => 'required|string|max:500',
            'residential_phone' => 'nullable|string|max:20',
            'residential_zipcode' => 'nullable|string|max:10',

            'permanent_address' => 'required|string|max:500',
            'permanent_phone' => 'nullable|string|max:20',
            'permanent_zipcode' => 'nullable|string|max:10',
        ];
    }

    public function ApplicantFullName()
    {
        return "$this->first_name $this->last_name";
    }

    public function HashedPassword()
    {
        return Hash::make($this->password);
    }

    public function Applicant()
    {
        return (new Applicant())->getFillable();
    }

    public function PersonalInformations()
    {
        return (new ApplicantInformation())->getFillable();
    }

    public function Educations()
    {
        return isset($this->education) ? $this->education : [];
    }
    public function Experiences()
    {
        return isset($this->work_experience) ? $this->work_experience : [];
    }
    public function Eligibilities()
    {
        return isset($this->eligibility) ? $this->eligibility : [];
    }

    public function Trainings()
    {
        return isset($this->training) ? $this->training : [];
    }

    public function References()
    {
        return isset($this->references) ? $this->references : [];
    }

    public function VoluntaryWorks()
    {
        return isset($this->voluntary_work) ? $this->voluntary_work : [];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($this->validator, response()->json([
            'message' => "Something went wrong.\n System validation failed",
            'errors' => $this->validator->errors(),
        ], 422));
    }
}
