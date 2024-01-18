<?php

namespace App\Service;

use GuzzleHttp\Client;

class SpacyApiService
{

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://spacy-api.doelia.fr',
        ]);
    }

    public function lemmatize(string $sentence): array
    {
        $response = $this->client->request('GET', '/lemmatize', [
            'query' => [
                'sentence' => $sentence,
            ],
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

}
