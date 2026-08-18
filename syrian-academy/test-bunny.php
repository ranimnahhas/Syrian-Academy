<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey = env('BUNNY_STREAM_API_KEY');
$libraryId = env('BUNNY_STREAM_LIBRARY_ID');

$client = new \GuzzleHttp\Client();

try {
    $response = $client->request('GET', "https://video.bunnycdn.com/library/{$libraryId}/videos/c6cff9bd-3bc9-46dc-8a0d-c576a8124a11", [
        'headers' => [
            'AccessKey' => $apiKey,
            'accept' => 'application/json',
        ],
    ]);
    echo "Response: " . $response->getBody() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}