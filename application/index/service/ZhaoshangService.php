<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/9/2021
 * Time: 上午11:19
 */

namespace app\index\service;
/*
 * 招商银行
 */
class ZhaoshangService extends  ConversionService
{

    public function escape() {
        $analyze = new PdfAnalyzeService($this->path);
        $name = uniqid();
        $params['format'] = 'csv';
        $params['outfile'] = $this->outdir . $name . ".csv";
        $params['lattice'] = false;
        $this->outfile = $params['outfile'];
        if (! empty($this->password)) {
            $params['password'] = $this->password;
        }
        $analyze->analyse($params);
    }

    public function map($res) {
        $list = [];
        $i = 0;
        foreach ( $res as $key => $val) {
            if (!isset($val[5])) {
                continue;
            }
//            $val[3] =str_replace(',', '', $val[3]);
//            $val[4] = str_replace(',', '', $val[4]);
            if (!is_numeric($val[2]) || ! is_numeric($val[3])) {
                if (empty($val[2])&& !empty($val[4])) {
                    $j = $i -1;
                    if (isset($list[$j][4])) {
                        $list[$j]['摘要'] .= $val[4];
                    }
                }
                continue;
            }
            $tmp = ['交易时间' => $val[0],'摘要' => $val[4],
                '交易方信息'=>$val[5],'账户余额'=>$val[3]];

            if ($val[2] >=0) {
                $tmp['收入'] = trim($val[2],"+");
            }else {
                $tmp['支出'] = trim($val[2],"-");
            }
            $list[$i] = $tmp;
            ++$i;
        }
        return $list;
    }

}