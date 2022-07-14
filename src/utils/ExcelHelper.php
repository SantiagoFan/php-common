<?php
/**
 * Created by PhpStorm.
 * User: Administrator-范文刚
 * Date: 2019/3/22
 * Time: 17:14
 */

namespace JoinPhpCommon\utils;

use PHPExcel_Style_Alignment;

/**
 * 通用 数据导出
 * Class ExcelHelper
 * @package app\common\utils
 */
class ExcelHelper
{
    const COLUMN_NAME =['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ'];
    public $objPHPExcel;
    public $sheet; // 工作表
    private $data_row = 0; // 数据总行数（不含标题和摘要）

    function __construct(){
        $this->objPHPExcel = new \PHPExcel();
        //-----全局样式------
        //默认字体、大小
        $this->objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        // 对齐方式
        $this->objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $this->objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->sheet = $this->objPHPExcel->getActiveSheet();
        //设置默认行高
        $this->sheet->getDefaultRowDimension()->setRowHeight(25);
        //设置默认列宽
        $this->sheet->getDefaultColumnDimension()->setWidth(15);
    }

    /**
     * 通用导出数据
     * @param $list array 详细数据
     * @param $field_map array 详细数据对应字段名称
     * @param $summary array 统计信息
     */
    public function DataToExcel($list,$field_map){
        // 设置标题行
        $this->setTitleRow($field_map);
        // 设置数据行
        $this->setDataRow($list,$field_map);
    }

    /**
     * 导出到浏览器
     */
    public function export($fileName){
        ob_end_clean();//清除缓冲区,避免乱码
        // 激活浏览器窗口
        header("Content-Disposition:attachment;filename=$fileName");
        //缓存控制
        header("Cache-Control:max-age=0");
        // 调用方法执行下载
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel,'Excel2007');
        // 数据流
        $objWriter->save("php://output");
    }

    /**
     * 导出到文件
     * @param $dir_path
     * @param $fileName
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportFile($dir_path,$fileName){
        if(!is_dir($dir_path)){mkdir($dir_path,0777,true);}

        ob_end_clean();//清除缓冲区,避免乱码
        $path=$dir_path."/".$fileName;
        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel,'Excel2007');
        $objWriter->save($path);
    }

    /**
     * 设置标题行样式
     */
    public function setTitleRow($field_map){
        // 设置标题行
        $colName='';
        foreach($field_map as $k=>$v){
            $colName = self::COLUMN_NAME[$k].'1';
            $this->sheet->setCellValue( $colName, $v['title']);
        }
        // 首行居中
        $this->sheet->getStyle('A1:'.$colName)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }

    /**
     * 设置数据
     * @param $list
     * @param $field_map
     */
    public function setDataRow($list,$field_map){
        // 给表格添加数据
        $this->data_row = 0;
        foreach ($list as $k=>$good){
            $row = $k+2;
            $this->data_row ++;
            foreach ($field_map as $col_index=>$col_info){
                $col_name = self::COLUMN_NAME[$col_index].$row;
                $value = $good[$col_info['key']];
                // 类型处理
                if(isset($col_info['type']) && $col_info['type']=='string'){
                    $value = "'".$value;
                }
                $this->sheet->setCellValue( $col_name,$value);
            }
        }
    }
}