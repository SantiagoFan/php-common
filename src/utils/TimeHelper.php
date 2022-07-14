<?php
/**
 * Created by PhpStorm.
 * User: Administrator-范文刚
 * Date: 2019/3/22
 * Time: 17:14
 */

namespace JoinPhpCommon\utils;


class TimeHelper
{
    /**
     * 时间区间转换
     * @param $type string day/month/year
     * @param $time string 2021-01-11
     *
     */
    public static function getTimeRange($type,$time){
        $start_time = date("Y-m-d 00:00:00");
        $end_time = date("Y-m-d 23:59:59");
        $s_time = strtotime($time);
        switch ($type){
            case 'day':
                $start_time = date("Y-m-d 00:00:00",$s_time);
                $end_time = date("Y-m-d 23:59:59",$s_time);
                break;
            case 'month':
                $start_time = date("Y-m-01 00:00:00",$s_time);
                $end_time = date('Y-m-d 23:59:59', strtotime("$start_time +1 month -1 day"));
                break;
            case 'year':
                $start_time = date("Y-01-01 00:00:00",$s_time);
                $end_time = date("Y-12-31 23:59:59",$s_time);
                break;
        }
        return [$start_time,$end_time];
    }
}