<?php
namespace JoinPhpCommon\tests\utils;
use JoinPhpCommon\utils\AdvancedQuery;
use PHPUnit\Framework\TestCase;
use think\db\Query;


class AdvancedQueryTest extends TestCase
{
    function testIndex(){
        // 查询条件 前台会的数据结构
        $condition =[
            'query'=>[
                "list"=>[],
                "condition"=>"and"
            ],
            'filter'=>[
                'name'=>'张三'
            ]
        ];
        $query =  new Query();
        // 高级条件
        AdvancedQuery::buildQuery($query,$condition);
        // 固化条件
        AdvancedQuery::buildFilter($query,$condition);

        Db('')::where($query)
            ->select();

    }
}