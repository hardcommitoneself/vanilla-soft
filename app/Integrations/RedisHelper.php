<?php

namespace App\Integrations;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Cache;

class RedisHelper implements RedisHelperInterface
{
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress): void
    {
        $data = [
            'subject' => $messageSubject,
            'email' => $toEmailAddress
        ];

        Cache::put((string) $id, $data, now()->addYear());
    }
}