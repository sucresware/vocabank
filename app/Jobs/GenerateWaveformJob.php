<?php

namespace App\Jobs;

use App\Models\Sample;
use FFMpeg\FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateWaveformJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sample;

    /**
     * Create a new job instance.
     */
    public function __construct($sample_id)
    {
        $this->sample = Sample::findOrFail($sample_id);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $waveform_name = $this->sample->id . '_waveform_' . time() . '.png';

        $ffmpeg = FFMpeg::create();
        $waveform = $ffmpeg
            ->open(Storage::path($this->sample->audio))
            ->waveform(1920, 128, ['#a0aec0']);
        $waveform->save(Storage::path('public/samples/' . $waveform_name));

        $this->sample->waveform = 'samples/' . $waveform_name;
        $this->sample->save();
    }
}
