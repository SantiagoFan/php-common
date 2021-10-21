<?php


namespace JoinPhpCommon\example\encrypt;

use JoinPhpCommon\encrypt\SM4;

class SM4Test
{
    public function index(){
        $SM4_KEY = '1076A3670BA7C124F0E43353C77686AA';
        $sm4 = new SM4();
        $port_data = json_encode(['name'=>'张三']);
        $biz_content = $sm4->encrypt($SM4_KEY, $port_data);
        echo $biz_content;
    }
}