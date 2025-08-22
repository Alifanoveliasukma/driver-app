<?php

namespace App\Services;

use Mtownsend\XmlToArray\XmlToArray;

class BaseApi
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
    }

    protected function sendRequest(string $body): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: text/xml; charset=utf-8",
            "Content-Length: " . strlen($body)
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode != 200 || $response === false) {
            return ['error' => 'The service is temporarily unavailable. Please try again later.'];
        }

        return XmlToArray::convert($response);
    }
}
