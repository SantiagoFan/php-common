<?php
/**
 * Created by PhpStorm.
 * User: Administrator-范文刚
 * Date: 2019/3/30
 * Time: 15:11
 */

namespace JoinPhpCommon\utils;

class Text
{
    /**
     * 生成随机唯一订单号
     * @return string
     */
    public static function build_order_no(){
        return
            date('Ymd').substr(
                implode(null,array_map('ord',str_split(substr(uniqid(), 7, 13), 1))),
                0, 8);
    }
    /**
    　　*
    　　* 思路:
    　　* step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
    　　* step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
    　　*/
    /**
     * 下划线转驼峰
     * 思路:
     * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
     * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
     * @param $uncamelized_words
     * @param string $separator 分隔符
     * @param bool $small 是否为小驼峰
     * @return string
     */
    public static function toCamel($uncamelized_words,$separator='_',$small = true): string
    {
        $uncamelized_words = ($small?$separator:''). str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }

    /**
　　* 驼峰命名转下划线命名
　　* 思路:
　　* 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
　　*/
    public static function toUnderline($camelCaps,$separator='_'): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
}