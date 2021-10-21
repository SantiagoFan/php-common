<?php
namespace JoinPhpCommon\example\utils;
class PinyinTest{
    function index(){
        $res = Pinyin::convert('我是个神仙');
        echo  $res;
    }
}
