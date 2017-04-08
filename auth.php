<?php

require 'app/bootstrap.php';

$auth = new \App\Auth\Social\Facebook($client);

header('Location: ' . $auth->authorizeUrl());
