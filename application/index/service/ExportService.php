<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午2:33
 */

namespace app\index\service;


class ExportService
{
    public static function export($list,$title ='')
    {
        // TODO: Implement export() method.
        $endTag = '</table></div></body><html>';
        $report_info=self::addHtmlHead($title);
        if(empty($list)) {
            $report_info.='<div class="table-condensed"><table border="1" cellspacing="0" cellpadding="0" rules="all">';
            $report_info.='<tr><td colspan="26" style="text-align: center">暂无数据！</td></tr>';
            $report_info.=$endTag;
            self::setHeader($title);
            exit($report_info);
        }

        $body = [
            ['name'=>'交易时间','index'=>'交易时间'],
            ['name'=>'交易方信息','index'=>'交易方信息'],
            ['name'=>'交易方账号','index'=>'交易方账号'],
            ['name'=>'交易类型','index'=>'交易类型'],
            ['name'=>'收入','index'=>'收入'],
            ['name'=>'支出','index'=>'支出'],
            ['name'=>'收入/支出','index'=>'收入/支出'],
            ['name'=>'账户余额','index'=>'账户余额'],
            ['name'=>'摘要','index'=>'摘要'],
            ['name'=>'备注','index'=>'备注'],
            ['name'=>'交易流水号','index'=>'交易流水号'],
        ];

        $columnBody =self::body($list,$body);
        $report_info.= $columnBody . $endTag ;

        self::setHeader($title);
        exit($report_info);
    }

    private  static function addHtmlHead($title){
        //head中添加的xml是为了导出的excel默认显示网格线
        $report_info='<html xmlns:x="urn:schemas-microsoft-com:office:excel"><head><!--[if gte mso 9]><xml>
            <x:ExcelWorkbook>
                <x:ExcelWorksheets>
                    <x:ExcelWorksheet>
                        <x:Name>'.$title.'</x:Name>
                        <x:WorksheetOptions>
                            <x:Print>
                                <x:ValidPrinterInfo />
                            </x:Print>
                        </x:WorksheetOptions>
                    </x:ExcelWorksheet>
                </x:ExcelWorksheets>
            </x:ExcelWorkbook>
        </xml>
        <![endif]--><meta http-equiv=Content-Type content="text/html; charset=utf-8"></head><body>';
        return $report_info;
    }

    private  static function setHeader($title){
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$title.date('Ymdhis') .".xls");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/download");;
        header("Pragma: no-cache");
        header("Expires: 600");
    }

    public static function body($list,$body) {
        $report_info='<div class="table-condensed"><table border="1">';
        $report_info.='<tr></tr>';

        //行头
        $startTr= '<tr style="text-align: center;width:100%">';
        $endTr = "</tr>";

        $columnTd = '';
        //行内容
        foreach ($body as $value) {
            $columnTd .= "<td>{$value['name']}</td>";
        }
        $columnBody =  $startTr. $columnTd . $endTr;

        foreach ($list as $item) {
            $columnTd = '';
            foreach ($body as $value) {
                $tdcontent = '';
                if (isset($item[$value['index']])) {
                    $tdcontent = $item[$value['index']];
                }
                $columnTd .= "<td>{$tdcontent}</td>";
            }
            $columnBody .=  $startTr. $columnTd . $endTr;
        }
        $report_info.= $columnBody  ;
        return $report_info;
    }

}