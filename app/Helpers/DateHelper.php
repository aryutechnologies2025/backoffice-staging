<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatDate($date, $format = 'm/d/Y')
    {
        if (empty($date)) {
            return 'N/A';
        }

        try {
            return Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return $date; // Return original if parsing fails
        }
    }
}

