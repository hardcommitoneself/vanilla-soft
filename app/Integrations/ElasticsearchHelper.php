<?php

namespace App\Integrations;

use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;
use Illuminate\Support\Str;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    public Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();

        $params = [
            'index' => 'emails',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'subject' => ['type' => 'text'],
                        'body' => ['type' => 'text'],
                        'email' => ['type' => 'text'],
                    ],
                ],
            ],
        ];

        if(!$this->client->indices()->exists(['index' => 'emails'])) {
            $this->client->indices()->create($params);
        }
    }

    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed
    {
        $params = [
            'index' => 'emails',
            'id' => (string) Str::uuid(), // Unique ID for the email
            'body' => [
                'subject' => $messageSubject,
                'body' => $messageBody,
                'email' => $toEmailAddress,
            ],
        ];

        return $this->client->index($params);
    }
}