<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/9/2021
 * Time: 下午2:58
 */

namespace app\index\service;

class GongshangService extends  ConversionService
{
    public function map($res) {
        /*
         * 交易时间, 交易方信息, 交易方账号, 交易类型, 收入, 支出, 收入/支出, 账户余额, 摘要, 备注, 交易流水号
         */
        $list = [];
        foreach ( $res as $key => $val) {
            if (!isset($val[11])) {
                continue;
            }
            $val[8] =str_replace(',', '', $val[8]);
            $val[9] = str_replace(',', '', $val[9]);
            if (!is_numeric($val[8]) || ! is_numeric($val[9])) {
                continue;
            }
            $tmp = ['交易时间' => $val[0],'摘要' => $val[6],
                '交易方信息'=>$val[10],'交易方账号'=>$val[11],'账户余额'=>$val[9]];

            if ($val[8] >=0) {
                $tmp['收入'] = trim($val[8],"+");
            }else {
                $tmp['支出'] = trim($val[8],"-");
            }
            $list[] = $tmp;
        }
        return $list;
    }

}