<?php


namespace JoinPhpCommon\tests\utils;

use JoinPhpCommon\utils\HttpHelper;
use PHPUnit\Framework\TestCase;

class HttpHelperTest extends TestCase
{
    public function testHttp(){
        $http = new HttpHelper();
        $res = $http->post_json('http://t.cn/index/index/timeout',['a'=>1,'b'=>2]);
        vdump($res);
    }
}