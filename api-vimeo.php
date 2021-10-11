<?php

ini_set('display_errors', 'On');
ini_set('memory_limit', '500M');
error_reporting(E_ALL);

if (file_exists('vendor/autoload.php')) {
	require_once('vendor/autoload.php');
} else {
	require_once(__DIR__ . '/../autoload.php');
}

$config = require_once('config.php');
require_once('API.php');
require_once('Task.php');