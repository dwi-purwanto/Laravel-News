<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Log;
use App\Events\Logging;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Logging  $event
     * @return void
     */
    public function handle(Logging $event)
    {
        $user = $event->user;
        $activity = $event->activity;

        $data = [
            'user_id' => $user->id,
            'activity' => 'Admin '.$user->name.' '.$activity.' news',
        ]; 
        return Log::create($data);
    }
}
