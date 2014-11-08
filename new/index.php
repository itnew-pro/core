<?php

use system\App;

$config = __DIR__ . "/config/main.php";
require(__DIR__ . "/system/App.php");

App::run($config);