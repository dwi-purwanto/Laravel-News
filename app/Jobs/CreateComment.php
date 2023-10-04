<?php

namespace App\Jobs;

use App\Models\NewsComment;
use App\Traits\ResponseAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ResponseAPI;

    protected $news; 
    protected $request;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($news, $request, $user)
    {
        $this->request = $request->comment;
        $this->news = $news;
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $data = [
                'user_id' => $this->user->id,
                'news_id' => $this->news->id,
                'comment' => $this->request
            ];

            $data = NewsComment::create($data);
            DB::commit();
            return $this->success('comment created', $data, 200);
        } catch(\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
        }
    }
}
