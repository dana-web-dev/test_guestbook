<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:4096', // 4 MB
            'captcha' => ['required', function ($attribute, $value, $fail) {

                $secret = config('app.captcha.secret_key');
                $response = $value; 

                $data = [
                    'secret' => $secret,
                    'response' => $response,
                    'remoteip' => request()->ip(),
                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Send as application/x-www-form-urlencoded

                $response = curl_exec($ch);

                curl_close($ch);
                $response_data = json_decode($response, true);

                if ( !$response_data['success'] ) {
                    Log::notice("CAPTCHA verification failed. Error: " . implode(", ", $response_data['error-codes']));
                    $fail('CAPTCHA verification failed.');
                }

            }],
        ];
    }
}
