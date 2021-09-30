<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 29/9/2021
 * Time: 下午4:06
 */

namespace app\index\service;


class WeixinService extends  ConversionService
{

    /*
     *   $pdf = "https://t-static-mbs.100fintech.cn//borrower_new/2020/11/117344/loan_primary/loan_base_application_from/117344/1632817873267.pdf";
     *
     */
    public function escape() {
        $host =  config('parsing.host');
        $uri = ltrim($this->path,".");
         //需要配置域名
        $analyze = new ThirdPartyPdfService($host.$uri);
        $name = uniqid();
        $this->outfile = $this->outdir . $name . ".xlsx";
        $analyze->progressRate($this->outfile);
    }

    public function extract()
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->outfile);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->outfile);
        $sheet_count = $spreadsheet->getSheetCount();
        $index = 0;
        for ($i=0 ; $i < $sheet_count ; $i++) {
            $sheet = $spreadsheet->getSheet($i);
            foreach ($sheet->getRowIterator(0) as $row) {
                $tmp = [];
                foreach ($row->getCellIterator() as $cell) {
                    $tmp[] = $cell->getFormattedValue();
                }
                $res[$index] = $tmp;
                ++$index;
            }
        }
        $list = $this->map($res);

        ExportService::export($list,$this->outname);
    }


    public function map($res) {
        /*
         * 交易时间, 交易方信息, 交易方账号, 交易类型, 收入, 支出, 收入/支出, 账户余额, 摘要, 备注, 交易流水号
         */
        $list = [];
        foreach ( $res as $key => $val) {
            if (!isset($val[6])) {
                continue;
            }
            $val[5] =str_replace(',', '', $val[5]);
            if (!is_numeric($val[5]) ) {
                continue;
            }
            $tmp = [ '交易流水号'=>$val[0],'交易时间' => $val[1],'交易类型'=>$val[2],
                '交易方信息'=>  $val[6], '备注'=>$val[3]];
            if ($val[3] =='收入') {
                $tmp['收入'] = trim($val[5],"+");
            }else {
                $tmp['支出'] = trim($val[5],"-");
            }
            $list[] = $tmp;
        }
        return $list;
    }

}