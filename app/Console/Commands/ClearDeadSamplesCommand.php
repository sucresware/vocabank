<?php

namespace App\Console\Commands;

use App\Models\Sample;
use Illuminate\Console\Command;

class ClearDeadSamplesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'samples:clear-dead';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dead_samples = Sample::query()
            ->where(function ($q) {
                $q->where('status', Sample::STATUS_DRAFT)
                ->orWhere('status', Sample::STATUS_PROCESSING);
            })->where('updated_at', '<=', now()->subDay());

        $this->info($dead_samples->count() . ' sample(s) to delete...');

        $dead_samples->each(function ($sample) {
            $sample->delete();
        });

        $this->info('OK!');
    }
}
