<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 01.09.2019
 * Time: 19:32
 */

namespace App\Library\Date;

use Carbon\Carbon;

trait BlogDateFormat
{
    /**
     * Change created_at date format.
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->calendar();
    }

    /**
     * Change updated_at date format.
     *
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->calendar();
    }

//    /**
//     * Change email_verified_at date format.
//     *
//     * @param $date
//     * @return string
//     */
//    public function getEmailVerifiedAtAttribute($date)
//    {
//        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->isoFormat('lll');
//    }
}