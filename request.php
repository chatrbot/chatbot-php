<?php
class APIRequest
{
    private $baseUrl;
    private $token;

    function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    private function post($uri, $data)
    {
        $url = sprintf("http://%s/api/%s?token=%s", $this->baseUrl, $uri, $this->token);
        $headers = ['Content-Type: application/json; charset=utf-8'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    function sendMessage($toUser, $content)
    {
        return $this->post("v1/chat/sendText", json_encode(["toUser" => $toUser, "content" => $content]));
    }
}
