<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/9/2021
 * Time: 下午3:01
 */

namespace app\index\service;


class PinganService extends  ConversionService
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
        /*
         * 交易时间, 交易方信息, 交易方账号, 交易类型, 收入, 支出, 收入/支出, 账户余额, 摘要, 备注, 交易流水号
         */
        $list = [];
        foreach ( $res as $key => $val) {
            if (!isset($val[5])) {
                continue;
            }
            $val[2] =str_replace(',', '', $val[2]);
            $val[3] = str_replace(',', '', $val[3]);
            if (!is_numeric($val[2]) || ! is_numeric($val[3])) {
                continue;
            }
            $tmp = ['交易时间' => $val[1],'摘要' => $val[4],
                '交易方信息'=>$val[5],'账户余额'=>$val[3]];

            if ($val[2] >=0) {
                $tmp['收入'] = trim($val[2],"+");
            }else {
                $tmp['支出'] = trim($val[2],"-");
            }
            $list[] = $tmp;
        }
        return $list;
    }

}