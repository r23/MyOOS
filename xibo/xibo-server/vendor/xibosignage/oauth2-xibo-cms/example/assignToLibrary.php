<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2015 Spring Signage Ltd
 * (assignToLibrary.php)
 */
require '../vendor/autoload.php';

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a provider
$provider = new \Xibo\OAuth2\Client\Provider\Xibo([
    'clientId' => 'p8kh8tq2mknOqMFx7qcgl7FGtFGDlDAlDOxb6TP1',    // The client ID assigned to you by the provider
    'clientSecret' => 'KjHPCQHm0ztqA4bcqP1dszYpLpcZqyAvaFlGbFZsq6HUn15ND8d8bZZhpFiPHWqMOQx5sXsAPgdtahICgtdhgFxxOAtlv59kl1GZZLe6dRNvOYQLQyXP9NtxfQkHgHj2wmJwhhhBwqvyPnp9pn13eevMCbDnqfyZJzMkUoG3fofxQPq6Kl9Mh5DtFtiEgXs2XE7zhKfGPOLWH1pUZxn3FLixOehSRUyuUB7SLDqnulPxlFMbV6L4EN4pAG5cRN',   // The client password assigned to you by the provider
    'redirectUri' => '',
    'baseUrl' => 'http://192.168.0.28'
]);

if (!isset($argv[1])) {
    $token = $provider->getAccessToken('client_credentials')->getToken();
    echo 'Token for next time: ' . $token;
    exit;
}
else
    $token = $argv[1];

try {
    $playlistId = 55;
    $media = [71, 77];

    // Prepare a file upload
    $guzzle = $provider->getHttpClient();
    $response = $guzzle->request('POST', 'http://192.168.0.28/api/playlist/library/assign/' . $playlistId, [
        'headers' => [
            'Authorization' => 'Bearer ' . $token
        ],
        'form_params' => [
            'media' => $media
        ]
    ]);


    // Get both
    echo 'Body: ' . $response->getBody() . PHP_EOL;
}
catch (\GuzzleHttp\Exception\RequestException $e) {
    echo 'Client Exception: ' . $e->getMessage() . PHP_EOL;

    if ($e->hasResponse()) {
        echo $e->getResponse()->getBody() . PHP_EOL;
    }
}