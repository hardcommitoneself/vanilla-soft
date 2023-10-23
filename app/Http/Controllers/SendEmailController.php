<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Integrations\ElasticsearchHelper;
use App\Integrations\RedisHelper;
use App\Mail\SendVanillaSoftMail;

class SendEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        /** @var ElasticsearchHelper $elasticsearchHelper */
        $elasticsearchHelper = app()->make(ElasticsearchHelper::class);

        /** @var RedisHelper $redisHelper */
        $redisHelper = app()->make(RedisHelper::class);

        /** Send Email to reciepient users */
        $emails = $request->input('emails');

        foreach($emails as $email) {
            Mail::to(data_get($email, 'email'))
                ->queue(new SendVanillaSoftMail($email));
        
            /** Store mail information on elasticsearch */
            $result = $elasticsearchHelper->storeEmail(data_get($email, 'body'), data_get($email, 'subject'), data_get($email, 'email'));
        
            /** Cache mail information on redis */
            $redisHelper->storeRecentMessage(data_get($result, '_id'), data_get($email, 'subject'), data_get($email, 'email'));   
        }

        return response()->json(['message' => 'Emails have been sent successfully']);
    }
}
