<?php

namespace App\Services;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class GmailService
{
    public function showMessages($email)
    {
        $messages = LaravelGmail::message()->from($email)->take(10)->preload()->all();
        $mailHistory = [];

        foreach ( $messages as $key => $message ) {
            $mailHistory[$key]['subject'] = $message->getSubject();
            $mailHistory[$key]['body'] = $message->getPlainTextBody();
        }

        return $mailHistory;
    }

    public function markAsRead($email)
    {
        $messages = LaravelGmail::message()->from($email)->take(10)->all();

        foreach ( $messages as $message ) {
            $message->markAsRead();
        }
    }

    public function getAllUnreadEmailsSenders()
    {
        $messages = collect(LaravelGmail::message()->unread()->preload()->all());
        $fromList = [];

        $messages->each(function ($message) use (&$fromList) {
            $fromList[] = $message->getFrom();
        });

        return $this->getUnreadEmailsList($fromList);
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
