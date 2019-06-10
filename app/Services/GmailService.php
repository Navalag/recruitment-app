<?php

namespace App\Services;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class GmailService
{
    public function showMessages()
    {
//        $messages = LaravelGmail::message()->unread()->preload()->all();
        $messages = LaravelGmail::message()->unread()->preload()->all( $pageToken = null );

        foreach ( $messages as $message ) {
            $body = $message->getHtmlBody();
            $subject = $message->getSubject();
            dump($subject, $body);
        }
        die();
    }

    public function getAllUnreadEmailsSenders()
    {
        $messages = $this->getAllUnreadEmails();

        $fromList = [];

        $messages->each(function ($message) use (&$fromList) {
            $fromList[] = $message->getFrom();
        });

        return $this->getUnreadEmailsList($fromList);
    }

    private function getAllUnreadEmails()
    {
        return collect(LaravelGmail::message()->unread()->preload()->all());
    }

    private function getUnreadEmailsList($fromList)
    {
        $senderList = [];

        foreach ($fromList as $item) {
            $senderList[] = $item['email'];
        }

        return collect($senderList);
    }
}
