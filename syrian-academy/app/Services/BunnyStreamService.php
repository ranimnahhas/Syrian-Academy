<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class BunnyStreamService
{
    private string $libraryId;
    private string $tokenKey;
    private string $hostname;

    public function __construct()
    {
        $this->libraryId = config('services.bunny.library_id');
        $this->tokenKey = config('services.bunny.token_key');
        $this->hostname = config('services.bunny.hostname');
    }

    public function getEmbedUrl(string $videoId, int $expiresInSeconds = 3600): string
    {
        $expires = time() + $expiresInSeconds;
        $token = hash('sha256', $this->tokenKey . $videoId . $expires);

        return "https://{$this->hostname}/embed/{$this->libraryId}/{$videoId}?token={$token}&expires={$expires}";
    }
  public function getVideoInfo(string $videoId): ?array
{
    $apiKey = config('services.bunny.api_key');
    $libraryId = config('services.bunny.library_id');

    $client = new \GuzzleHttp\Client();

    try {
        $response = $client->request('GET', "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}", [
            'headers' => [
                'AccessKey' => $apiKey,
                'accept' => 'application/json',
            ],
        ]);

        $video = json_decode($response->getBody(), true);

        return [
            'duration' => $video['length'] ?? null,
            'title'    => $video['title'] ?? null,
            'views'    => $video['views'] ?? 0,
            'width'    => $video['width'] ?? null,
            'height'   => $video['height'] ?? null,
        ];
    } catch (\Exception $e) {
        return null;
    }
}
}