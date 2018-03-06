<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\UserCreate as MailUserCreate;
use App\Mail\UserActivate as MailUserActivate;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $link = false)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ( ! empty($this->link)) {
            $this->user->activate_link = $this->link;
            Mail::to($this->user->email)->send(new MailUserActivate($this->user));
        } else {
            Mail::to($this->user->email)->send(new MailUserCreate($this->user));
        }
    }
}
