<?php

namespace App\Models;

use App\Helpers\SucresHelper;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use CyrildeWit\EloquentViewable\Viewable;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sample extends Model implements ViewableContract
{
    use Viewable, HasSettingsTable;

    const STATUS_DRAFT = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_PUBLIC = 2;

    protected $casts = [
        'settings' => 'array',
    ];

    protected $hidden = [
        'thumbnail', 'waveform', 'audio',
    ];

    protected $appends = [
        'views', 'presented_date', 'thumbnail_url', 'waveform_url',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublic($query)
    {
        return $query->whereStatus(static::STATUS_PUBLIC);
    }

    public function getNextAttribute()
    {
        return static::public()->where('created_at', '>', $this->created_at)->orderBy('created_at', 'asc')->first();
    }

    public function getPrevAttribute()
    {
        return static::public()->where('created_at', '<', $this->created_at)->orderBy('created_at', 'desc')->first();
    }

    public function getViewsAttribute()
    {
        return $this->views()->count();
    }

    public function getPresentedDateAttribute()
    {
        $markup = SucresHelper::niceDate($this->created_at, SucresHelper::NICEDATE_WITH_HOURS);

        return $markup;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? Storage::disk('public')->url($this->thumbnail) : '/img/default.png';
    }

    public function getWaveformUrlAttribute()
    {
        return $this->waveform ? Storage::disk('public')->url($this->waveform) : '/img/waveform.png';
    }
}
