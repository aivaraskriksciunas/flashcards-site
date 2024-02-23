<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() 
    {
        return view( 'pages.home' );
    }

    /**
     * Creates a proxy to the phpmyadmin container
     *
     * @param Request $request
     * @param string $path
     * @return void
     */
    public function phpmyadmin( Request $request, string $path = '' )
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request( 
            $request->method(), 
            "http://phpmyadmin/$path",
            [
                'query' => $request->query->all(),
                'body' => $request->getContent(),
            ]
        );

        return response( $response->getBody(), $response->getStatusCode(), $response->getHeaders() );
    }
}
