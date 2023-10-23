<?php

namespace Tests\Unit;

use Tests\TestCase;

class SendEmailTest extends TestCase
{
    public function testEmailsHaveBeenSentSuccessfully()
    {
        $emailData = [
            ['email' => 'recipient1@example.com', 'body' => 'Email body 1', 'subject' => 'Subject 1'],
            ['email' => 'recipient2@example.com', 'body' => 'Email body 2', 'subject' => 'Subject 2'],
        ];

        $response = $this->post('/api/send', ['emails' => $emailData]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Emails have been sent successfully']);
    }
}