<?php

abstract class Plugin
{
    protected $bot;

    function __construct($bot)
    {
        $this->bot = $bot;
    }

    abstract function do($message);
}

class DogPlugin extends Plugin
{
    function construct($bot)
    {
        parent::__construct($bot);
    }

    private function get()
    {
        $apiURL = "https://v1.alapi.cn/api/dog?format=json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    function do($message)
    {
        if ($message->content != "舔狗") {
            return;
        }
        $res = json_decode($this->get(), true);
        if ($res && $res['code'] === 200) {
            $content = $res['data']['content'];
            $toUser = $message->fromUser;

            echo "content:" . $content . PHP_EOL;

            $this->bot->sendMessage($toUser, $content);
        }
    }
}
