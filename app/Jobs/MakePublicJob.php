<?php

namespace App\Jobs;

use App\Models\Sample;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MakePublicJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sample;
    protected $sample_id;

    /**
     * Create a new job instance.
     */
    public function __construct($sample_id)
    {
        $this->sample_id = $sample_id;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->sample = Sample::findOrFail($this->sample_id);
        $this->sample->disableLogging();

        $this->sample->status = Sample::STATUS_PUBLIC;
        $this->sample->save();
    }
}
