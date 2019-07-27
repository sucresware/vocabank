<?php

namespace App\Models;

use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use CyrildeWit\EloquentViewable\Viewable;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model implements ViewableContract
{
    use Viewable, HasSettingsTable;

    const STATUS_DRAFT = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_PUBLIC = 2;
    const STATUS_UNLISTED = 3;
    const STATUS_REMOVED = 4;

    protected $casts = [
        'options' => 'array',
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
        return static::public()->where('id', '>', $this->id)->orderBy('id', 'asc')->first();
    }

    public function getPrevAttribute()
    {
        return static::public()->where('id', '<', $this->id)->orderBy('id', 'desc')->first();
    }
}
