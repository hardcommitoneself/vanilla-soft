<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Integrations\ElasticsearchHelper;
use App\Utilities\Contracts\RedisHelperInterface;
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

        /** Send Email to reciepient users */
        $emails = $request->input('emails');

        foreach($emails as $email) {
            Mail::to(data_get($email, 'email'))
                ->queue(new SendVanillaSoftMail($email));
        
            $elasticsearchHelper->storeEmail(data_get($email, 'body'), data_get($email, 'subject'), data_get($email, 'email'));
        }

        /** @var RedisHelperInterface $redisHelper */
        // $redisHelper = app()->make(RedisHelperInterface::class);
        // TODO: Create implementation for storeRecentMessage and uncomment the following line
        // $redisHelper->storeRecentMessage(...);

        return response()->json(['message' => 'Emails have been sent successfully']);
    }
}
