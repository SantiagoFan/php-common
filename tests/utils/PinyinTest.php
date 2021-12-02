<?php
namespace JoinPhpCommon\tests\utils;

use JoinPhpCommon\utils\Pinyin;
use PHPUnit\Framework\TestCase;

class PinyinTest extends TestCase{

    /**
     * 方法名称已test开始
     */
    function  testPinyin(){
        $res = Pinyin::convert('我是个神仙');
        echo $res;
        // 相等断言
        $this->assertEquals(
            $res,
            'wo shi ge shen xian'
        );
    }
}
