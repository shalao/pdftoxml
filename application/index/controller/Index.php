<?php
namespace app\index\controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Index
{

    public  $field = ['交易时间','交易方信息','交易方账号','交易类型','收入','支出','收入/支出','账户余额','摘要','备注','交易流水号'];

    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    //table
    public function hello() {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        //$reader = IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load("../uploads/交通银行2.csv");
        $sheet_count = $spreadsheet->getSheetCount();
        $index = 0;
        for ($i=0 ; $i < $sheet_count ; $i++) {
            $sheet = $spreadsheet->getSheet($i);
            foreach ($sheet->getRowIterator(1) as $row) {
                $tmp = [];
                foreach ($row->getCellIterator() as $cell) {
                    //$tmp[] = str_replace(PHP_EOL,'', $cell->getFormattedValue());
                    $tmp[] = $cell->getFormattedValue();
                }
                $res[$index] = $tmp;
                ++$index;
            }
        }
        var_dump($res);die;
    }


    public function helloold2() {


    }

    public function helloold($name = 'ThinkPHP5')
    {
        echo "fffff";die;
        $reader = IOFactory::createReader("Csv");
        $title = "中国农业银行流水";
        $spreadsheet = $reader->load($title.".csv");
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($sheet->getRowIterator(1) as $row) {
            $tmp = [];
            foreach ($row->getCellIterator() as $cell) {
                //$tmp[] = str_replace(PHP_EOL,'', $cell->getFormattedValue());
                $tmp[] = $cell->getFormattedValue();
            }
            $res[$row->getRowIndex()] = $tmp;
        }

        $list = self::map($res);
        self::export($list,$title);

    }

    //["交易日期", "交易时间", "交易摘要", "交易金额", "本次余额", "对手信息", "交易渠道", "交易附言"]
    public function map($res) {
        $list = [];
        foreach ( $res as $key => $val) {
            if (! (is_numeric($val[0]) && is_numeric($val[3]))) {
                continue;
            }
            $tmp = ['交易时间' => $val[0],'摘要'=> $val[2],'备注' => $val[7],'交易类型'=> $val[6],'交易方信息'=>$val[5],'账户余额'=>$val[4]];


            if ($val[3] >=0) {
                $tmp['收入'] = $val['3'];
            }else {
                $tmp['支出'] = $val['3'];
            }
            $list[] = $tmp;
        }
        return $list;
    }

    public static function export($list,$title ='') {

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

    private static function addHtmlHead($title){
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

    private static function setHeader($title){
        header("Content-Type: application/vnd.ms-excel; name='excel'");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$title.date('Ymd') .".xls");
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
