<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncUnreadEmailsJob;
use App\Applicant;

class SyncUnreadEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:gmail-api-unread-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync with gmail api all unread emails from applicants.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $applicants = Applicant::all();

        SyncUnreadEmailsJob::dispatchNow($applicants);
    }
}
