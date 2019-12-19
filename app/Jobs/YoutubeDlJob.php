<?php

namespace App\Jobs;

use App\Models\Sample;
use FFMpeg\FFProbe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use YoutubeDl\YoutubeDl;

class YoutubeDlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $sample;
    protected $sample_id;

    /**
     * Create a new job instance.
     */
    public function __construct($sample_id, $url)
    {
        $this->url = $url;
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

        if (!Storage::disk('public')->exists('samples/')) {
            Storage::disk('public')->makeDirectory('samples/', 0775, true);
        }

        $audio_name = $this->sample->id . '_youtubedl_' . time() . '.mp3';

        $dl = new YoutubeDl([
            'extract-audio' => true,
            'audio-format'  => 'mp3',
            'audio-quality' => 0, // best
            'output'        => $audio_name,
        ]);
        $dl->setDownloadPath(Storage::disk('public')->path('samples/'));
        $dl->download($this->url);

        $ffprobe = FFProbe::create();
        $duration = $ffprobe->format(Storage::disk('public')->path('samples/' . $audio_name))->get('duration');

        $this->sample->audio = 'samples/' . $audio_name;
        $this->sample->duration = $duration;

        $this->sample->save();
    }
}
