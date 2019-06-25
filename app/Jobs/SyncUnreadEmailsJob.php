<?php

namespace App\Jobs;

use App\Applicant;
use App\Services\GmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;

class SyncUnreadEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection
     */
    private $applicants;

    /**
     * Create a new job instance.
     *
     * @param Collection $applicants
     * @return void
     */
    public function __construct(Collection $applicants)
    {
        $this->applicants = $applicants;
    }

    /**
     * Sync Unread emails count via Gmail api
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $unreadEmailList = app(GmailService::class)->getAllUnreadEmailsSenders();

            $this->applicants->transform(function (Applicant $applicant) use ($unreadEmailList) {

                $applicant->update(['unread_emails_count' => $unreadEmailList->filter(function ($value, $key) use ($applicant) {
                    return $applicant->email == $value;
                })->count()]);

                return $applicant;
            });
        } catch (\Exception $e) {
            // TODO: fix message to show it when a user next time refresh a page
            \Session::flash('flash_message', 'Please <a href="/gmail-settings">Sign In</a> with Gmail oauth again.');
        }
    }
}
