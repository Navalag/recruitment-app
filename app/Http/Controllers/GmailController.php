<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class GmailController extends Controller
{
    public function showMessages()
    {
//        $messages = LaravelGmail::message()->unread()->preload()->all();
        $messages = LaravelGmail::message()->preload()->all( $pageToken = null );

        foreach ( $messages as $message ) {
            $body = $message->getHtmlBody();
            $subject = $message->getSubject();
            dump($subject, $body);
        }
        die();
    }
}
