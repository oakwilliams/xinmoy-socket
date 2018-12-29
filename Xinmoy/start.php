<?php
/*
 * Start
 *
 * @author Oak Williams <oakwilliams@gmail.com>
 * @date   05/05/2018
 *
 * @copyright 2018 Xinmoy, Inc. All Rights Reserved.
 */


require_once __DIR__ . '/../vendor/autoload.php';


use Xinmoy\Lib\Log;
use Xinmoy\Register\Register;
use App\Server;


/**
 * Handle exception.
 *
 * @param Exception $e exception
 */
function handle_exception($e) {
    $file = $e->getFile();
    $line = $e->getLine();
    $message = $e->getMessage();
    $trace = $e->getTraceAsString();
    Log::getInstance()->log("$file($line): $message\n$trace");
}


/**
 * Start register.
 *
 * @param array $config config
 */
function start_register($config) {
    if (empty($config)) {
        throw new Exception('wrong config');
    }

    if (empty($config['register']['host']) || !isset($config['register']['port']) || ($config['register']['port'] < 0)) {
        throw new Exception('wrong host/port');
    }

    $register = new Register($config['register']['host'], $config['register']['port']);
    $register->start();
}


/**
 * Start server.
 *
 * @param array $config config
 */
function start_server($config) {
    // Config
    if (empty($config)) {
        throw new Exception('wrong config');
    }

    if (empty($config['server']['host']) || !isset($config['server']['port']) || ($config['server']['port'] < 0)) {
        throw new Exception('wrong host/port');
    }

    if (empty($config['register']['host']) || !isset($config['register']['port']) || ($config['register']['port'] < 0)) {
        throw new Exception('wrong register host/port');
    }

    // Databases
    $databases = file_get_contents(__DIR__ . '/../databases.json');
    $databases = json_decode($databases, true);
    if (empty($databases)) {
        $databases = null;
    }

    if (!isset($databases['master'])) {
        $databases['master'] = null;
    }

    if (!isset($databases['slaves'])) {
        $databases['slaves'] = [];
    }

    // Caches
    $caches = file_get_contents(__DIR__ . '/../caches.json');
    $caches = json_decode($caches, true);
    if (empty($caches)) {
        $caches = null;
    }

    if (!isset($caches['master'])) {
        $caches['master'] = null;
    }

    if (!isset($caches['slaves'])) {
        $caches['slaves'] = [];
    }

    // Start server.
    $server = new Server($config['server']['host'], $config['server']['port']);
    $server->setRegisterAddress($config['register']['host'], $config['register']['port']);
    $server->setMySQLMaster($databases['master']);
    $server->setMySQLSlaves($databases['slaves']);
    $server->setRedisMaster($caches['master']);
    $server->setRedisSlaves($caches['slaves']);
    $server->start();
}


// Set exception handler.
set_exception_handler('handle_exception');

// Read config.
$config = file_get_contents(__DIR__ . '/../config.json');
$config = json_decode($config, true);
if (empty($config)) {
    throw new Exception('wrong config');
}

if (!empty($config['timezone'])) {
    date_default_timezone_set($config['timezone']);
}

if (empty($config['role'])) {
    throw new Exception('wrong role');
}

$function = "start_{$config['role']}";
if (!function_exists($function)) {
    throw new Exception('nonexisted role');
}

// Start.
$function($config);
