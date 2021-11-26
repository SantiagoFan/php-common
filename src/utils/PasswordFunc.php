<?php
/**
 * User: 王鹏   
 * Date: 2021/11/26
 */

namespace JoinPhpCommon\utils;

class PasswordFunc
{
    /**
     * 生成密码
     * @return string
     */
    public static function func_passwd($passwd, $salt = '') {

            return md5(md5($passwd) . $salt);
    }
}