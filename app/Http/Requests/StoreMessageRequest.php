<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\RecaptchaService;

class StoreMessageRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:4096', // 4 MB
            'captcha' => ['required', function ($attribute, $value, $fail) {

                $recaptcha = app(RecaptchaService::class);

                if (!$recaptcha->verify($value, request()->ip())) {
                    $fail('CAPTCHA verification failed.');
                }

            }],
        ];

        return $rules;
    }
}
