<?php

namespace JoinPhpCommon\tests\utils;

use PHPUnit\Framework\TestCase;
use JoinPhpCommon\utils\Validate ;

class ValidateTest extends TestCase
{
    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @param mixed $callback 回调方法（闭包）
     * @return array|string|true
     * @throws ValidateException
     */
    public function testValidate()
    {
        $Validate = new Validate();
        //要验证的数据
        $params = [
            'id'=>1,
            'name'=>'seraph'
        ];
        $validate = [
            'id' => 'require|number',
            'name' => 'require',
            'num' => 'require|number',
        ];
        $msg = [
            'id.require' => 'id不能为空',
            'name' => '名称不能为空',
            'num.require' => '数量不能为空',
            'num.number' => '数量只能是数字',
        ];
        //表单验证
        $val = $Validate->validate($params, $validate, $msg);
        if ($val !== true) {
            //验证不通过返回对应的提示
            $this->error($val);
        }
    }

}
