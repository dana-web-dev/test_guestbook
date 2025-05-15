<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    public function verify(string $token, string $ip): bool
    {
        $data = [
            'secret' => config('app.captcha.secret_key'),
            'response' => $token,
            'remoteip' => $ip,
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
            Log::notice("CAPTCHA verification failed.",  [
                'errors' => $response_data['error-codes'] ?? [],
            ]);
            return false;
        }

        return true;
    }
}
