<?php

namespace Core\Voice;

class Api
{
    public function call(): bool|string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.assemblyai.com/v2/transcript',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(
                [
                    'audio_url' => 'https://bit.ly/3yxKEIY'
                ]
            ),
            CURLOPT_HTTPHEADER => [
                'authorization: 63859e3a5e9f4739af78178376bfa395',
                'content-type: application/json',
            ],
        ]);
        $response = curl_exec($curl);
//        $err = curl_error($curl);
        curl_close($curl);
//        if ($err) {
//            echo 'cURL Error #:' . $err;
//        } else {
        return $response;
//        }
    }
}