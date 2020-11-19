<?php
require 'vendor/autoload.php';
require_once 'chatbot.php';
require_once 'plugin.php';

//修改为自己的token
$token = "";
$host = "118.25.84.114:18881";

$chatbot = new ChatBot($host, $token);

$dogPlugin = new DogPlugin($chatbot);

$chatbot->use($dogPlugin);

$chatbot->start();
