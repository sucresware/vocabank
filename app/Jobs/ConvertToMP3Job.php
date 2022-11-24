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

        $this->sample->status = Sample::STATUS_PROCESSING;
        $this->sample->save();

        if (! Storage::disk('public')->exists('samples/')) {
            Storage::disk('public')->makeDirectory('samples/', 0775, true);
        }

        $audio_name = $this->sample->id . '_audio_' . time() . '.mp3';
        $unprocessed_file = Storage::disk('local')->path($this->sample->audio);

        $ffprobe = FFProbe::create();
        $duration = $ffprobe->format($unprocessed_file)->get('duration');

        $ffmpeg = FFMpeg::create();
        $ffmpeg
            ->open($unprocessed_file)
            ->save((new Mp3()), Storage::disk('public')->path('samples/' . $audio_name));

        Storage::disk('local')->delete($this->sample->audio);

        $this->sample->audio = 'samples/' . $audio_name;
        $this->sample->duration = $duration;

        $this->sample->save();
    }
}
