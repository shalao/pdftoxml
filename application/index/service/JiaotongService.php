<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 29/9/2021
 * Time: 上午11:42
 */

namespace app\index\service;


class JiaotongService  extends  ConversionService
{

    public function escape()
    {
        $analyze = new PdfAnalyzeService($this->path);
        $name = uniqid();
        $params['format'] = 'csv';
        $params['outfile'] = $this->outdir . $name . ".csv";
        $params['lattice'] = false;
        $this->outfile = $params['outfile'];
        if (!empty($this->password)) {
            $params['password'] = $this->password;
        }

        $analyze->analyse($params);

//        $this->outfile = "../uploads/20210930/交通银行2.csv";
    }


    public function map($res) {
        /*
         * 交易时间, 交易方信息, 交易方账号, 交易类型, 收入, 支出, 收入/支出, 账户余额, 摘要, 备注, 交易流水号
         */
        $list = [];
        $i = 0;
        foreach ( $res as $key => $val) {
            if (!isset($val[9])) {
                continue;
            }
            //错位
            if (is_numeric($val[3]) && is_numeric($val[4]) && strlen($val[4]) >= 9) {
                $val[9] = $val[8];
                $val[8] = $val[7];
                $val[7] = $val[6];
                $val[6] = $val[5];
                $val[5] = $val[4];
                $val[4] = $val[3];
                $val[3] = $val[2];
            }
            $val[4] =str_replace(',', '', $val[4]);
            $val[5] = str_replace(',', '', $val[5]);
            if (!is_numeric($val[4]) || ! is_numeric($val[5])) {
                if (empty($val[4]) && !empty($val[9])) {
                    $j = $i - 1;
                    if (isset($list[$j][9])) {
                        $list[$j]['摘要'] .= $val[9];
                    }
                }
                continue;
            }

            $receivable = explode(" ",$val[3]);

            if (empty($receivable[0]) || ! in_array($receivable[0],['贷','借'])) {
                continue;
            }
            $other = explode(' ',$val[7]);

            $tmp = ['交易时间' => $val[1],'交易类型'=>$val[2], '交易方信息'=>$other[0],'交易方账号'=>$val[6],'账户余额'=>$val[5],'摘要'=>$val[9]];
            if ($receivable[0] =='贷') {
                $tmp['收入'] = trim($val[4],"+");
            }else {
                $tmp['支出'] = trim($val[4],"-");
            }
            $list[$i] = $tmp;
            ++$i;
        }

        return $list;
    }

}