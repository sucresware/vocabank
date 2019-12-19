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
    protected $sample_id;

    /**
     * Create a new job instance.
     */
    public function __construct($sample_id)
    {
        $this->sample_id = \Hashids::connection('samples')->decode($sample_id)[0] ?? null;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->sample = Sample::findOrFail($this->sample_id);
        $this->sample->disableLogging();

        $this->sample->status = Sample::STATUS_PROCESSING;
        $this->sample->save();

        if (!Storage::disk('public')->exists('images/')) {
            Storage::disk('public')->makeDirectory('images/', 0775, true);
        }

        $waveform_name = $this->sample->id . '_waveform_' . time() . '.png';

        $ffmpeg = FFMpeg::create();
        $waveform = $ffmpeg
            ->open(Storage::disk('public')->path($this->sample->audio))
            ->waveform(1920, 128, ['#a0aec0']);
        $waveform->save(Storage::disk('public')->path('images/' . $waveform_name));

        $this->sample->waveform = 'images/' . $waveform_name;
        $this->sample->save();
    }
}
