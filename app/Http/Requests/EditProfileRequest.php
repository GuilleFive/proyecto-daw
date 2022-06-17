<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => ['required','string', Rule::unique('users', 'username')->ignore(Auth::user()->id)],
            'phone' => ['required', 'numeric', 'digits:9', Rule::unique('users', 'phone')->ignore(Auth::user()->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(Auth::user()->id)],
            'new_password' => 'string|nullable|min:8|max:20',
        ];
    }
}
