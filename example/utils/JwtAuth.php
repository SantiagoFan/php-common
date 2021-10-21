<?php

use JoinPhpCommon\utils\JwtAuth;

$jwt = new JwtAuth('test',
    'dsadadsads',
    't.cn',
    't.cn'
);
$data = [
    'uid'=>299632,
    'name'=>'张三'
];
// 生成token
$token = $jwt->CreateToken($data);
vdump('TOKEN: '.$token);
// 验证
$is_success = $jwt->validateToken($token);
vdump("is_success".$is_success);
// 解析数据
$data_2 = $jwt->parseToken($token);
vdump($data_2);