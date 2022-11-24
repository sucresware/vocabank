<?php

namespace App\Models;

use App\Helpers\SucresHelper;
use Conner\Likeable\Likeable;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class Sample extends Model
{
    use HasSettingsTable, LogsActivity, Likeable, SoftDeletes;

    const STATUS_DRAFT = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_PUBLIC = 2;

    protected $casts = [
        'settings' => 'array',
    ];

    protected $hidden = [
        'thumbnail', 'waveform', 'audio', 'settings', 'updated_at', 'user_id', 'uuid',
    ];

    protected $appends = [
        'presented_date', 'thumbnail_url', 'waveform_url', 'liked',
    ];

    protected $guarded = [];

    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected static function boot(): void
    {
        parent::boot();

        self::deleting(function ($sample) {
            $sample->tags()->detach();

            collect(Storage::disk('local')->allFiles('temp'))
                ->filter(function ($file) use ($sample) {
                    return preg_match('/' . $sample->id . '_/', $file);
                })
                ->each(function ($file) {
                    Storage::disk('local')->delete($file);
                });

            collect(Storage::disk('public')->allFiles())
                ->filter(function ($file) use ($sample) {
                    return preg_match('/' . $sample->id . '_/', $file);
                })
                ->each(function ($file) {
                    Storage::disk('public')->delete($file);
                });
        });
    }

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

    public function getPresentedDateAttribute()
    {
        $markup = SucresHelper::niceDate($this->created_at, SucresHelper::NICEDATE_WITH_HOURS);

        return $markup;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? Storage::disk('public')->url($this->thumbnail) : url('/img/default.png');
    }

    public function getWaveformUrlAttribute()
    {
        return $this->waveform ? Storage::disk('public')->url($this->waveform) : url('/img/waveform.png');
    }

    public function getLikedAttribute()
    {
        if (auth()->user()) {
            return $this->liked();
        }

        return null;
    }

    public function getIdAttribute()
    {
        return \Hashids::connection('samples')->encode($this->attributes['id']);
    }

    public function getRealIdAttribute()
    {
        return $this->attributes['id'];
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $id = \Hashids::connection('samples')->decode($value)[0] ?? null;

        return $this
            ->where('id', $id)
            ->orWhere('uuid', $value)
            ->first() ?? abort(404);
    }
}
