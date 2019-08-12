<?php

namespace App\Jobs;

use App\Models\Sample;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ConvertToMP3Job implements ShouldQueue
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
        $audio_name = $this->sample->id . '_audio_' . time() . '.mp3';

        $ffmpeg = FFMpeg::create();
        $ffmpeg
            ->open(Storage::path($this->sample->audio))
            ->save((new Mp3()), Storage::path('samples/' . $audio_name));

        $ffprobe = FFProbe::create();
        $duration = $ffprobe->format(Storage::path('samples/' . $audio_name))->get('duration');

        $this->sample->audio = 'samples/' . $audio_name;
        $this->sample->duration = $duration;

        $this->sample->save();
    }
}
