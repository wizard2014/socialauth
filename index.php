<?php

require 'app/bootstrap.php';

$auth = new \App\Auth\Social\Facebook($client);

if (!isset($_GET['code'])) {
    echo 'Ivalid code';
    exit;
}

$user = $auth->getUser($_GET['code']);

var_dump($user);
