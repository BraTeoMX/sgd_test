<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class CorreoController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('correo.index');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

          //Remplazamos los valores de las variables de entorno de correo

        $direnv=base_path('.env');
        $envdata =
            'APP_NAME=\'' . env('APP_NAME'). "'\n" .
            'APP_ENV=' . env('APP_ENV') . "\n" .
            'APP_KEY=' . env('APP_KEY') . "\n" .
            'APP_DEBUG=' . env('APP_DEBUG') . "\n" .
            'APP_URL=' . env('APP_URL') . "\n\n" .
            'LOG_CHANNEL=' . env('LOG_CHANNEL'). "\n" .
            'LOG_SLACK_WEBHOOK_URL=' . env('LOG_SLACK_WEBHOOK_URL') . "\n" .
            'PAPERTRAIL_URL=' . env('PAPERTRAIL_URL') . "\n" .
            'PAPERTRAIL_PORT=' . env('PAPERTRAIL_PORT'). "\n\n" .
            'DB_CONNECTION=' . env('DB_CONNECTION') . "\n" .
            'DB_HOST=' . env('DB_HOST')  . "\n" .
            'DB_PORT=' . env('DB_PORT')  . "\n" .
            'DB_DATABASE=' . env('DB_DATABASE') . "\n" .
            'DB_USERNAME=' . env('DB_USERNAME') . "\n" .
            'DB_PASSWORD=' . env('DB_PASSWORD') . "\n\n" .
            'BROADCAST_DRIVER=' . env('BROADCAST_DRIVER')  . "\n" .
            'CACHE_DRIVER=' . env('CACHE_DRIVER') . "\n" .
            'QUEUE_CONNECTION=' . env('QUEUE_CONNECTION') . "\n" .
            'SESSION_DRIVER=' . env('SESSION_DRIVER') . "\n" .
            'SESSION_LIFETIME=' . env('SESSION_LIFETIME') . "\n\n" .
            'REDIS_HOST=' . env('REDIS_HOST'). "\n" .
            'REDIS_PASSWORD=' . env('REDIS_PASSWORD') . "\n" .
            'REDIS_PORT=' . env('REDIS_PORT')  . "\n\n" .
            'MAIL_DRIVER=' . env('MAIL_DRIVER')  . "\n" .
            'MAIL_HOST=' . $request->host . "\n" .
            'MAIL_PORT=' . $request->port . "\n" .
            'MAIL_USERNAME=' . $request->user. "\n" .
            'MAIL_PASSWORD=' . $request->password  . "\n" .
            'MAIL_ENCRYPTION=' . $request->encryption . "\n" .
            'MAIL_TO_ADDRESS=' . env('MAIL_TO_ADDRESS') . "\n" .
            'MAIL_FROM_ADDRESS=' . env('MAIL_FROM_ADDRESS')  . "\n" .
            'MAIL_FROM_NAME=\'' . env('MAIL_FROM_NAME')  . "'\n\n" .
            'PUSHER_APP_ID=' . env('PUSHER_APP_ID') . "\n" .
            'PUSHER_APP_KEY=' . env('PUSHER_APP_KEY')  . "\n" .
            'PUSHER_APP_SECRET=' . env('PUSHER_APP_SECRET')  . "\n" .
            'PUSHER_APP_CLUSTER=' . env('PUSHER_APP_CLUSTER') . "\n\n" .
            'MIX_PUSHER_APP_KEY=' . env('MIX_PUSHER_APP_KEY')  . "\n" .
            'MIX_PUSHER_APP_CLUSTER=' . env('MIX_PUSHER_APP_CLUSTER=') . "\n\n" .
            'SCOUT_DRIVER=' . env('SCOUT_DRIVER')  . "\n" .
            'ALGOLIA_APP_ID=' . env('ALGOLIA_APP_ID')  . "\n" .
            'ALGOLIA_SECRET=' . env('ALGOLIA_SECRET') . "\n\n" .
            'JWT_SECRET=' . env('JWT_SECRET') . "\n" .
            'JWT_TTL=' .env('JWT_TTL');


        file_put_contents($direnv, $envdata);


        return redirect()->route('correo.index');
    }


}
