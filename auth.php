<?php

require 'app/bootstrap.php';

$auth = new \App\Auth\Social\GitHub($client);

header('Location: ' . $auth->authorizeUrl());
