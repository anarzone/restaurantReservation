<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Netgroup\AtaTechSms\BroadcastController;

class SendReservationSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $title;
    protected $phoneNumbers;

    /**
     * Create a new job instance.
     *
     * @param $broadcastSms
     * @param $title
     * @param $phoneNumbers
     */
    public function __construct($title, $phoneNumbers)
    {
        $this->title = $title;
        $this->phoneNumbers = $phoneNumbers;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new BroadcastController())->sendIndividualMessage($this->title, 'now', $this->phoneNumbers);
    }
}
