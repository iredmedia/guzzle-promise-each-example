<?php

$loader = require __DIR__ . '/vendor/autoload.php';

$client = new GuzzleHttp\Client();
$userNames= ['iredmedia', 'laravel'];
$results = [];

// Generator for the promises
$promises = (function () use ($client, $userNames) {
    foreach ($userNames as $username) {
        yield $client->requestAsync('GET', 'https://api.github.com/users/'.$username);
    }
})();

// Wait for all to finish, then append results to array
foreach (GuzzleHttp\Promise\all($promises)->wait() as $response) {
    $results[] = $profile = json_decode($response->getBody(), true);
}

// Pretty Output
highlight_string("<?php\n\$data =\n" . var_export($results, true) . ";\n?>");
