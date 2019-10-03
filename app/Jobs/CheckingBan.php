<?php

namespace App\Jobs;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckingBan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->users = User::get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user) {
            // Check if ban time has passed
            if (!is_null($user->banned_until) && Carbon::parse($user->banned_until)->isBefore(Carbon::now())) {
                $user->banned_until = null;
                $user->save();
            }
        }
    }
}
