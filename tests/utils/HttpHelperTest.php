<?php


namespace JoinPhpCommon\tests\utils;

use JoinPhpCommon\utils\HttpHelper;
use PHPUnit\Framework\TestCase;

class HttpHelperTest extends TestCase
{
    public function testHttp(){
        $http = new HttpHelper();
        $res = $http
            ->setBaseUrl('')
            ->setRequestContentType(HttpHelper::TYPE_FROM)
            ->post('http://t.cn/index/index/timeout',['a'=>1,'b'=>2]);
        vdump($res);
    }
}