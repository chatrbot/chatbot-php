<?php
require_once "request.php";

use Amp\Websocket\Client;

class ChatBot
{
    private $host;
    private $token;
    private $plugins = [];
    private $req;

    function __construct($host, $token)
    {
        $this->host = $host;
        $this->token = $token;
        $this->req = new APIRequest($host, $token);
    }

    function start()
    {
        Amp\Loop::run(function () {
            $connection = yield Client\connect('ws://' . $this->host . '/ws?token=' . $this->token);

            echo "websocket连接成功" . PHP_EOL;

            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();
                printf("Received: %s\n", $payload);

                $message = json_decode($payload);
                if ($message) {
                    //文本消息
                    if ($message->msgType === 1) {
                        foreach ($this->plugins as $plugin) {
                            $plugin->do($message);
                        }
                    }
                }
            }
        });
    }

    function use($plguin)
    {
        $this->plugins[] = $plguin;
    }

    function sendMessage($toUser, $content)
    {
        try {
            $this->req->sendMessage($toUser, $content);
        } catch (Exception $e) {
            echo "send message error:" . $e->getMessage() . PHP_EOL;
        }
    }
}
