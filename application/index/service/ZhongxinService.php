<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 27/9/2021
 * Time: 下午2:16
 */

namespace app\index\service;

/**
 * Class ZhongxinService   中信银行
 * @package app\index\service
 *
 */
class ZhongxinService  extends  ConversionService
{
    public function escape()
    {
        $analyze = new PdfAnalyzeService($this->path);
        //读取和写入都不支持中文名
        $name = uniqid();
        $params['format'] = 'csv';
        $params['outfile'] = $this->outdir . $name . ".csv";
        $params['lattice'] = false;
        $this->outfile = $params['outfile'];
        $analyze->analyse($params);
    }

    public function map($res)
    {
        $list = [];
        foreach ($res as $key => $val) {

            if (!isset($val[5])) {
                continue;
            }

            if (empty($val[3]) || (empty($val[1]) && empty($val[2]))) {
                continue;
            }

            $val[1] = trim($val[1]);
            $val[2] = trim($val[2]);
            $val[3] = trim($val[3]);

            $receivable = explode(" ",$val[1]);
            $paid = explode(" ",$val[2]);
            $balance = explode(" ",$val[3]);

            if (! (isset($receivable[1]) && is_numeric($receivable[1]) || isset($paid[1]) && is_numeric($paid[1]) ))
            {
                continue;
            }

            if (! (isset($balance[1]) && is_numeric($balance[1])) )
            {
                continue;
            }

            $tmp = ['交易时间' => $val[0], '备注' => $val[4], '交易方信息' => $val[5], '账户余额' => $balance[1]];

            if (isset($receivable[1]) && is_numeric($receivable[1]) ) {
                $tmp['收入'] = $receivable[1];
            } else {
                $tmp['支出'] = $paid[1];
            }
            $list[] = $tmp;
        }
        return $list;
    }
}



