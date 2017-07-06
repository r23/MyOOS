#!/usr/bin/env php
<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2015-2017 Spring Signage Ltd
 * (index.php)
 *
sequenceDiagram
Player->> CMS: Register
Note right of Player: Register contains the XMR Channel
CMS->> XMR: PlayerAction
XMR->> CMS: ACK
XMR-->> Player: PlayerAction
 *
 */
require 'vendor/autoload.php';

function exception_error_handler($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

$config = 'config.json';

if (!file_exists('config.json'))
    $config = (Phar::running(false) == '') ? __DIR__ : dirname(Phar::running(false)) . '/config.json';

if (!file_exists($config))
    throw new InvalidArgumentException('Missing ' . $config . ' file, please create one in the same folder as the application');

chdir(dirname($config));

$config = json_decode(file_get_contents($config));

if ($config->debug)
    $logLevel = \Monolog\Logger::DEBUG;
else
    $logLevel = \Monolog\Logger::WARNING;

// Set up logging to file
$log = new \Monolog\Logger('xmr');
$log->pushHandler(new \Monolog\Handler\StreamHandler('log.txt', $logLevel));
$log->pushHandler(new \Monolog\Handler\StreamHandler(STDOUT, $logLevel));
$log->info(sprintf('Starting up - listening for CMS on %s.', $config->listenOn));

try {
    $loop = React\EventLoop\Factory::create();

    $context = new React\ZMQ\Context($loop);

    // Reply socket for requests from CMS
    $responder = $context->getSocket(ZMQ::SOCKET_REP);
    $responder->bind($config->listenOn);

    // Set RESP socket options
    if (isset($config->ipv6RespSupport) && $config->ipv6RespSupport === true) {
        $log->debug('RESP MQ Setting socket option for IPv6 to TRUE');
        $responder->setSockOpt(\ZMQ::SOCKOPT_IPV6, true);
    }

    // Pub socket for messages to Players (subs)
    $publisher = $context->getSocket(ZMQ::SOCKET_PUB);

    // Set PUB socket options
    if (isset($config->ipv6PubSupport) && $config->ipv6PubSupport === true) {
        $log->debug('Pub MQ Setting socket option for IPv6 to TRUE');
        $publisher->setSockOpt(\ZMQ::SOCKOPT_IPV6, true);
    }

    foreach ($config->pubOn as $pubOn) {
        $log->info(sprintf('Bind to %s for Publish.', $pubOn));
        $publisher->bind($pubOn);
    }

    // REP
    $responder->on('error', function ($e) use ($log) {
        $log->error($e->getMessage());
    });

    $responder->on('message', function ($msg) use ($log, $responder, $publisher) {

        try {
            // Log incoming message
            $log->info($msg);

            // Parse the message and expect a "channel" element
            $msg = json_decode($msg);

            if (!isset($msg->channel))
                throw new InvalidArgumentException('Missing Channel');

            if (!isset($msg->key))
                throw new InvalidArgumentException('Missing Key');

            if (!isset($msg->message))
                throw new InvalidArgumentException('Missing Message');

            // Respond to this message
            $responder->send(true);

            // Push message out to subscribers
            $publisher->sendmulti([$msg->channel, $msg->key, $msg->message]);
            //$publisher->send('cms ' . $msg);
        }
        catch (InvalidArgumentException $e) {
            // Return false
            $responder->send(false);

            $log->error($e->getMessage());
        }
    });

    // Periodic updater
    $loop->addPeriodicTimer(30, function() use ($log, $publisher) {
        $log->debug('Heartbeat...');
        $publisher->sendmulti(["H", "", ""]);
    });

    // Run the react event loop
    $loop->run();
}
catch (Exception $e) {
    $log->error($e->getMessage());
    $log->error($e->getTraceAsString());
}
