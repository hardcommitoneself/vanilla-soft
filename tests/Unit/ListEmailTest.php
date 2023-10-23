<?php

namespace Tests\Unit;

use App\Integrations\ElasticsearchHelper;
use Tests\TestCase;

class ListEmailTest extends TestCase
{
    public function testTheAmountOfSentEmailsIsCorrect()
    {
        /** @var ElasticsearchHelper $elasticsearchHelper */
        $elasticsearchHelper = app()->make(ElasticsearchHelper::class);

        $emails = $elasticsearchHelper->retrieveEmails('emails');

        $response = $this->get('/api/list');

        $this->assertSame(count(data_get($response, 'hits.hits')), count(data_get($emails, 'hits.hits')));
    }
}