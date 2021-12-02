<?php


namespace JoinPhpCommon\tests\utils;

use PHPUnit\Framework\TestCase;
use JoinPhpCommon\utils\Text;

class TextTest extends TestCase
{
    public function testIndex(){
        $order_code = Text::build_order_no();
        echo $order_code;
    }
}