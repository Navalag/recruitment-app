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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $unreadEmailList = ( new GmailService() )->getAllUnreadEmailsSenders();

            $this->applicants->transform(function (Applicant $applicant) use ($unreadEmailList) {
                $applicant->update(['unread_emails_count' => $this->countUnreadEmails($unreadEmailList, $applicant)]);

                return $applicant;
            });
        } catch (\Exception $e) {
            // TODO: decide how to handel this exception
        }
    }

    private function countUnreadEmails($unreadEmailList, $applicant)
    {
        $count = 0;

        $unreadEmailList->each(function ($email) use ($applicant, &$count) {
            if ($email === $applicant->email) $count++;
        });

        return $count;
    }
}
