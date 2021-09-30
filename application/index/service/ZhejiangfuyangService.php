<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/9/2021
 * Time: 上午11:18
 */

namespace app\index\service;

/**
 * Class ZhejiangfuyangService  浙江富阳农村商业银行
 * @package app\index\service
 *
 */
class ZhejiangfuyangService extends  ConversionService
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
        /*
        * 交易时间, 交易方信息, 交易方账号, 交易类型, 收入, 支出, 收入/支出, 账户余额, 摘要, 备注, 交易流水号
        */
        foreach ( $res as $key => $val) {
            if (!isset($val[9])) {
                continue;
            }
            //错位
            if (is_numeric($val[2]) && is_numeric($val[4]) && strlen($val[4]) == 9) {
                $val[9] = $val[8];
                $val[8] = $val[7];
                $val[7] = $val[6];
                $val[6] = $val[5];
                $val[5] = $val[4];
                $val[4] = $val[3];
                $val[3] = $val[2];
            }

            $val[3] =str_replace(',', '', $val[3]);
            $val[4] = str_replace(',', '', $val[4]);
            if (!is_numeric($val[3]) || ! is_numeric($val[4])) {
                continue;
            }
            $tmp = ['交易时间' => $val[0],'摘要'=>$val[2],'备注' => $val[9],
                '交易方信息'=>$val[6],'交易方账号'=>$val[5],'账户余额'=>$val[4]];

            if ($val[3] >=0) {
                $tmp['收入'] = trim($val[3],"+");
            }else {
                $tmp['支出'] = trim($val[3],"-");
            }
            $list[] = $tmp;
        }
        return $list;
    }

}