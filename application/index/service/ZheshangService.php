<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 27/9/2021
 * Time: 下午6:31
 */

namespace app\index\service;

use PhpOffice\PhpSpreadsheet\Reader\Csv as Csv;

/*
 * 浙商银行
 */

class ZheshangService extends  ConversionService
{
    /*
    * 读csv 内容
    */
    public function extract()
    {
        $reader = new Csv();
        $reader->setDelimiter(',');
        $reader->setEnclosure('');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->outfile);
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($sheet->getRowIterator() as $row) {
            $tmp = [];
            foreach ($row->getCellIterator() as $cell) {
                $tmp[] = $cell->getFormattedValue();
            }
            $res[$row->getRowIndex()] = $tmp;
        }
        $list = $this->map($res);
        ExportService::export($list,$this->outname);
    }



    public function map($res) {
        $list = [];
        foreach ( $res as $key => $val) {
            if (!isset($val[7])) {
                continue;
            }

            $val[1] =str_replace(',', '', $val[1]);
            $val[2] = str_replace(',', '', $val[2]);
            if (!is_numeric($val[1]) || ! is_numeric($val[2])) {
                continue;
            }
            $tmp = ['交易时间' => $val[0],'备注' => $val[7],'摘要'=>$val[3],
                '交易方信息'=>$val[5],'交易方账号'=>$val[4],'账户余额'=>$val[2]];

            if ($val[1] >=0) {
                $tmp['收入'] = trim($val[1],"+");
            }else {
                $tmp['支出'] = trim($val[1],"-");
            }
            $list[] = $tmp;
        }
        return $list;
    }

}