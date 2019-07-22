<?php

namespace App\Models;

use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use CyrildeWit\EloquentViewable\Viewable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Regex\Regex;

class Sample extends Model implements ViewableContract
{
    use Viewable;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getVocarooIdAttribute()
    {
        $regex = Regex::match('/http(?:s|):\/\/vocaroo.com\/i\/((?:\w|-)*)/m', $this->vocaroo_link);

        if (!$regex->hasMatch()) {
            return null;
        }

        return $regex->group(1);
    }

    public function render($options = [])
    {
        views($this)
            ->delayInSession(1)
            ->record();

        $height = $options['height'] ?? '128';
        $controls = $options['controls'] ?? true;
        $autoplay = $options['autoplay'] ?? false;
        $uniqid = $options['uniqid'] ?? $this->id;

        $markup = '<div class="mb-3" style="height: ' . $height . 'px" data-wavesurfer data-src="/samples/' . $this->id . '/listen" data-id="' . $uniqid . '" data-height="' . $height . '" ' . ($autoplay ? 'data-autoplay' : '') . '>';
        $markup .= '</div>';

        if ($controls) {
            $markup .= '<div class="text-center mb-3">';
            $markup .= '<div class="bg-teal-400 hover:bg-teal-700 h-8 w-8 p-3 inline-flex items-center justify-center rounded-full text-white text-xs shadow-lg">';
            $markup .= '<a href="javascript:void(0)" class="animated fadeInRight" data-wavecontrol data-target="' . $uniqid . '" data-control="play"><i class="fas fa-fw fa-play"></i></a>';
            $markup .= '<a href="javascript:void(0)" class="hidden animated fadeInRight" data-wavecontrol data-target="' . $uniqid . '" data-control="pause"><small><i class="fas fa-fw fa-pause"></i></small></a>';
            $markup .= '</div>';
            $markup .= '</div>';
        }

        return $markup;
    }
}
