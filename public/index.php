<?php

use App\Core\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

session_start();

new Router();
