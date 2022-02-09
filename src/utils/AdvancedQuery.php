<?php


namespace JoinPhpCommon\utils;

use think\db\Query;

/**
 * AdvancedQuery 高级查询构造器
 * $condition 接口
 * {
 *  page:1,
 *  limit:20,
 *  order:'id',
 *  sort:'desc'
 *  query:{
list:[
 *              { name:'nickname',op:'like',value:'张'  }
 *          ]
 *      condition:'and'
 *   },
 *  filter:{
state:1,
 *      cate:[1,2]
 *  }
 * }
 *
 *
 * @package app\common
 */
class AdvancedQuery
{
    /**
     * 构建高级查询条件
     * @param $query Query
     * @param $condition array
     * @return Query
     */
    public static function buildQuery(Query $query, array $condition): Query
    {
        // 不含有效的条件
        if(!isset($condition['query']) or count($condition['query']['list'])==0){
            return;
        }
        $separator = (isset($condition['query']['condition']) && $condition['query']['condition']== 'or') ?' or ':' and ';
        $sql = self::buildList($condition['query']['list'],$separator);
        $sql && $query->whereRaw($sql);
    }

    /**
     * 构建数组条件
     * @param $list 条件数组
     * @param $separator 链接符
     * @return string
     */
    public static function buildList($list,$separator){
        // 不含有效的条件
        if (count($list)==0){
            return '';
        }
        $where = [];
        // sql 转换
        foreach ($list as $k=> $v){
            // 未设置字段名称
            if ($v['field']=='') {
                continue;
            }
            $sql = self::conversion($v);
            array_push($where,$sql);
        }
        // 拼接
        $separator = $separator == 'or'?' or ':' and ';
        return implode($separator,$where);
    }

    /**
     * 条件转换
     * @param $item
     * @return string sql
     */
    private static function conversion($item):string{
        switch ($item['op']){
            // 相等
            case 'eq': return sprintf("%s = '%s'",$item['field'] ,$item['value']); break;
            // 包含
            case 'contains': return sprintf("%s like '%%%s%%'",$item['field'] ,$item['value']); break;
            // 以..开始
            case 'start': return sprintf("%s like '%s%%'",$item['field'] ,$item['value']); break;
            // 以..结尾
            case 'end': return sprintf("%s like '%%%s'",$item['field'] ,$item['value']); break;
            // 在...中
            case 'in': return sprintf("locate(%s,'%s')>0",$item['field'],$item['value']); break;
            // 不等于
            case 'neq': return sprintf("%s != '%s'",$item['field'] ,$item['value']); break;
            // 大于
            case 'gt':  return sprintf("%s > '%s'",$item['field'] ,$item['value']); break;
            // 大于等于
            case 'egt': return sprintf("%s >= '%s'",$item['field'] ,$item['value']); break;
            // 小于
            case 'lt': return sprintf("%s < '%s'",$item['field'] ,$item['value']); break;
            // 小于等于
            case 'elt': return sprintf("%s <= '%s'",$item['field'] ,$item['value']); break;
        }
    }

    /**
     * 构建过滤条件
     * @param $query Query
     * @param $condition array
     * @return Query
     */
    public static function buildFilter(Query $query, array $condition): Query
    {
        if(!isset($condition['filter'])){
            return $query;
        }
        $where= [];
        foreach ($condition['filter'] as $k => $v) {
            if (is_array($v)) {
                if (count($v) == 0) {
                    continue;
                }
                $where[$k] = ['in', $v];
            } else {
                if ($v) {
                    $where[$k] = $v;
                }
            }
        }
        if(count($where)>0){
            $query->where($where);
        }
        return $query;
    }
}