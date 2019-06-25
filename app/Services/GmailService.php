<?php

namespace App\Services;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message\Mail;
use Illuminate\Support\Collection;

class GmailService
{
    /**
     * Send email.
     *
     * @param $to
     * @param $subject
     * @param $body
     * @param $time
     * @param $uniqueKey
     *
     * @return void
     * @throws
     */
    public function sendEmail($to, $subject, $body, $time, $uniqueKey): void
    {
        $mail = new Mail();

        $mail->to( $to, $name = null );
        $mail->subject( $subject );
        $mail->view( 'emails.test-task', [
            'uniqueKey' => $uniqueKey,
            'body'      => $body,
            'time'      => $time,
        ]);

        try {
            $mail->send();
        } catch (\Exception $e) {
            //TODO: handel the exception
        }
    }

    /**
     * Load all messages.
     *
     * @param $email
     * @return array
     */
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

    /**
     * Mark all messages as read.
     *
     * @param $email
     * @return void
     */
    public function markAsRead($email): void
    {
        $messages = LaravelGmail::message()->from($email)->take(10)->all();

        foreach ( $messages as $message ) {
            $message->markAsRead();
        }
    }

    /**
     * Get list of senders for all unread emails.
     *
     * @return Collection
     */
    public function getAllUnreadEmailsSenders()
    {
        $messages = collect(LaravelGmail::message()->unread()->preload()->all());
        $fromList = [];

        $messages->each(function ($message) use (&$fromList) {
            $fromList[] = $message->getFrom();
        });

        // TODO: can be optimized
        return $this->getUnreadEmailsList($fromList);
    }

    /**
     * Format list of senders.
     *
     * @param $fromList
     * @return Collection
     */
    private function getUnreadEmailsList($fromList)
    {
        $senderList = [];

        foreach ($fromList as $item) {
            $senderList[] = $item['email'];
        }

        return collect($senderList);
    }
}
