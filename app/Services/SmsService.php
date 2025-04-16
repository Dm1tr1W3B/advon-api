<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SmsService
{

    public function send($phone, $text)
    {
        $src = '<?xml version="1.0" encoding="UTF-8"?>
                <SMS>
                    <operations>
                    <operation>SEND</operation>
                    </operations>
                    <authentification>
                    <username>' . Config::get('api.sms.username') . '</username>
                    <password>' . Config::get('api.sms.password') . '</password>
                    </authentification>
                    <message>
                    <sender>' . Config::get('api.sms.sender_name') . '</sender>
                    <text>' . $text . '</text>
                    </message>
                    <numbers>
                    <number messageID="' . rand(10000, 99999) . '">' . $phone . '</number>
                    </numbers>
                </SMS>';
        $client = new Client();
        try {
            Log::info('SMS: ' . $phone);
            $resp = $client->request('POST', Config::get('api.sms.base_uri') . Config::get('api.sms.base_path'), [
                'form_params' => [
                    'XML' => $src,
                ]
            ]);
            Log::info('SMS: ' . $resp->getBody());
            $response = simplexml_load_string($resp->getBody());
            return (int)$response['status'] == 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return false|\SimpleXMLElement
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBalance()
    {
        $src = '<?xml version="1.0" encoding="UTF-8"?>
        <SMS>
            <operations>
            <operation>BALANCE</operation>
            </operations>
            <authentification>
                <username>' . Config::get('api.sms.username') . '</username>
                <password>' . Config::get('api.sms.password') . '</password>
            </authentification>
        </SMS>';

        $client = new Client();

        try {
            Log::info('SMS: getBalance');
            $resp = $client->request('POST', Config::get('api.sms.base_uri') . Config::get('api.sms.base_path'), [
                'form_params' => [
                    'XML' => $src,
                ]
            ]);
            Log::info('SMS: ' . $resp->getBody());
            // response = ["status"=>"0", "credits"=>"138", "amount"=>"56.38","currency": "UAH"]
            $response = simplexml_load_string($resp->getBody());
            return $response;
        } catch (\Exception $e) {
            return false;
        }
    }
}
