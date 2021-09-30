<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 27/9/2021
 * Time: 下午4:59
 */

namespace app\index\service;

/*
 * 建设银行
 */

class JiansheService  extends  ConversionService
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


    public function map($res)
    {
        $list = [];
        $i = 0;
        foreach ($res as $key => $val) {

            if (! isset($val[9])) {
                continue;
            }
            $val[6] =str_replace(',', '', $val[6]);
            $val[7] = str_replace(',', '', $val[7]);
            //交易日期,交易金额,账户余额,有问题跳过
            if ( !(isset($val[5]) && is_numeric($val[5])) || ! is_numeric($val[6]) || !is_numeric($val[7])) {
                $val[5] =str_replace(',', '', $val[5]);
                $val[6] = str_replace(',', '', $val[6]);

                if ( is_numeric($val[4]) && is_numeric($val[5]) && is_numeric($val[6])) {
                    $val[9] = $val[8];
                    $val[8] = $val[7];
                    $val[7] = $val[6];
                    $val[6] = $val[5];
                    $val[5] = $val[4];
                    if ( !(isset($val[5]) && is_numeric($val[5])) || ! is_numeric($val[6]) || !is_numeric($val[7])) {
                        continue;
                    }
                }else {
                    continue;
                }
            }

            $tmp = ['交易时间' => $val[5],'摘要' => $val[2], '备注' => $val[8],  '账户余额' => $val[7]];


            $account = explode("/",$val[9]);
            if (!empty($account[0])) {
                $tmp['交易方账号'] = $account[0];
            }

            if (!empty($account[1])) {
                $tmp['交易方信息'] = $account[1];
            }

            if ($val[6] >=0) {
                $tmp['收入'] = trim($val[6],"+");
            } else {
                $tmp['支出'] =  trim($val[6],"-");
            }
            $list[$i] = $tmp;
            ++$i;
        }

        return $list;
    }
}