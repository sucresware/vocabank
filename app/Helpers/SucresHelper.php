<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class SucresHelper
{
    const NICEDATE_MINIMAL = 0;
    const NICEDATE_WITH_HOURS_AND_SECONDS = 1;
    const NICEDATE_WITH_HOURS = 2;

    public static function niceDate(Carbon $date, $ret_type = self::NICEDATE_WITH_HOURS)
    {
        if ($date->isToday()) {
            $markup = 'aujourd\'hui';
        } elseif ($date->isLastDay()) {
            $markup = 'hier';
        } else {
            $markup = sprintf('le %s', $date->format('d/m/Y'));
        }

        switch ($ret_type) {
            case self::NICEDATE_WITH_HOURS_AND_SECONDS:
                $markup .= sprintf(' à %s', $date->format('H:i:s'));

                break;
            case self::NICEDATE_WITH_HOURS:
                $markup .= sprintf(' à %s', $date->format('H:i'));

                break;
            case self::NICEDATE_MINIMAL:
            default:
                break;
        }

        return $markup;
    }
}
