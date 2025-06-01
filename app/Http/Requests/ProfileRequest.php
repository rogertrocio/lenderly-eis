<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProfileRequest extends FormRequest
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
        $user = request()->user();

        return [
            'first_name' => ['required', 'min:2', 'max:255'],
            'last_name' => ['required', 'min:2', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'phone' => ['nullable', 'string'],
            'job' => ['nullable', 'string'],
            'avatar' => ['nullable', File::image()->min('1kb')->max('2mb')],
        ];
    }

    /**
     * Modify column values after validated.
     * Note: This is alternative for modifying columns since the function passedValidation() insnot working on validated data.
     *
     * @param [type] $key
     * @param [type] $default
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        $validated = $this->validator->validated();

        if (isset($validated['first_name']) && isset($validated['last_name'])) {
            $validated['name'] = sprintf('%s %s', $validated['first_name'], $validated['last_name']);
        }

        return $validated;
    }

}
