<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
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
        /** Send Email to reciepient users */
        $emails = $request->input('emails');

        foreach($emails as $email) {
            Mail::to(data_get($email, 'email'))
                ->queue(new SendVanillaSoftMail($email));
        }

        /** @var ElasticsearchHelperInterface $elasticsearchHelper */
        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
        // TODO: Create implementation for storeEmail and uncomment the following line
        // $elasticsearchHelper->storeEmail(...);

        /** @var RedisHelperInterface $redisHelper */
        $redisHelper = app()->make(RedisHelperInterface::class);
        // TODO: Create implementation for storeRecentMessage and uncomment the following line
        // $redisHelper->storeRecentMessage(...);

        return response()->json(['message' => 'Emails have been sent successfully']);
    }
}
