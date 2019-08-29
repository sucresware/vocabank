<?php

namespace App\Models;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    public static function boot()
    {
        parent::boot();

        self::saving(function (self $activity) {
            $activity->properties = $activity->properties->merge([
                'ip' => request()->ip(),
                'ua' => request()->userAgent(),
            ]);

            return $activity;
        });
    }
}
