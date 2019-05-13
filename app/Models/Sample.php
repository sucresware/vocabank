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

        $markup = '<div data-wavesurfer data-src="/samples/' . $this->id . '/listen" data-id="' . $uniqid . '" data-height="' . $height . '" ' . ($autoplay ? 'data-autoplay' : '') . '>';
        $markup .= '</div>';

        if ($controls) {
            $markup .= '<div class="btn-group d-block text-center mt-3">';
            $markup .= '<a href="javascript:void(0)" class="btn btn-outline-primary btn-sm" data-wavecontrol data-target="' . $uniqid . '" data-control="play"><small><i class="fas fa-fw fa-play"></i></small></a>';
            $markup .= '<a href="javascript:void(0)" class="btn btn-outline-primary btn-sm" data-wavecontrol data-target="' . $uniqid . '" data-control="pause"><small><i class="fas fa-fw fa-pause"></i></small></a>';
            $markup .= '<a href="javascript:void(0)" class="btn btn-outline-primary btn-sm" data-wavecontrol data-target="' . $uniqid . '" data-control="stop"><small><i class="fas fa-fw fa-stop"></i></small></a>';
            $markup .= '</div>';
        }

        return $markup;
    }
}
