<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Integrations\ElasticsearchHelper;

class ListEmailController extends Controller
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

        $emails = $elasticsearchHelper->retrieveEmails('emails');

        return response()->json($emails);
    }
}
