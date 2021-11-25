<?php

namespace JoinPhpCommon\utils;
use Moment;

class DateUtils{
  
  /**
   * 只是简单方法，日期函数处理建议用这个 moment-php  风格类似momentjs
   * https://github.com/fightbulc/moment.php
   * 
   * composer require fightbulc/moment
   */

  

  /**  计算时间相差
   * @param $date1 结束时间
   * @param $date2 开始时间
   * @param $unit  s：秒  i：分  h：小时  d：天
   */
  public static function dateDiff($date1, $date2, $unit = ""){
    switch ($unit) {
      case 's':
        $dividend = 1;
        break;
      case 'i':
        $dividend = 60; 
        break;
      case 'h':
        $dividend = 3600;
        break;
      case 'd':
        $dividend = 86400;
        break; 
      default:
        $dividend = 86400;
    }
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    if ($time1 && $time2)
      return (float)($time1 - $time2) / $dividend;
    return false;
  }
}