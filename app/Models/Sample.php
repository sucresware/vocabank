<?php

namespace App\Models;

use App\Helpers\SucresHelper;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use CyrildeWit\EloquentViewable\Viewable;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Sample extends Model implements ViewableContract
{
    use Viewable, HasSettingsTable;

    const STATUS_DRAFT = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_PUBLIC = 2;
    const STATUS_UNLISTED = 3;
    const STATUS_REMOVED = 4;

    protected $casts = [
        'youtube_video' => 'array',
    ];

    protected $guarded = [];

    protected $appends = [
        'views', 'presented_date',
    ];

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
        return static::public()->where('id', '>', $this->id)->orderBy('id', 'asc')->first();
    }

    public function getPrevAttribute()
    {
        return static::public()->where('id', '<', $this->id)->orderBy('id', 'desc')->first();
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
}
