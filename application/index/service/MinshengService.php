<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/9/2021
 * Time: 下午4:09
 */

namespace app\index\service;


class MinshengService extends  ConversionService
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
            if (!isset($val[8])) {
                continue;
            }
            $val[4] =str_replace(',', '', $val[4]);
            $balance = explode(" ",$val[5]);
            $balance[0] =str_replace(',', '', $balance[0]);
            if (!is_numeric($val[4]) || ! is_numeric($balance[0])) {
                continue;
            }

            $account = explode("/",$val[8]);
            $tmp = ['交易时间' => $val[2],'摘要' => $val[3], '账户余额'=>$balance[0]];

            if (! empty($account[0])) {
                $tmp['交易方信息'] = $account[0];
            }
            if (! empty($account[1])) {
                $tmp['交易方账号'] = $account[1];
            }
            if ($val[4] >=0) {
                $tmp['收入'] = trim($val[4],"+");
            }else {
                $tmp['支出'] = trim($val[4],"-");
            }
            $list[] = $tmp;
        }
        return $list;
    }


}