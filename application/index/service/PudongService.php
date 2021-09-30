<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/9/2021
 * Time: 下午3:02
 */

namespace app\index\service;


class PudongService  extends  ConversionService
{
    public function map($res) {
        /*
         * 交易时间, 交易方信息, 交易方账号, 交易类型, 收入, 支出, 收入/支出, 账户余额, 摘要, 备注, 交易流水号
         */
        $list = [];
        foreach ( $res as $key => $val) {
            if (!isset($val[7])) {
                continue;
            }
            $val[3] =str_replace(',', '', $val[3]);
            $val[4] = str_replace(',', '', $val[4]);
            if (!is_numeric($val[3]) || ! is_numeric($val[4])) {
                continue;
            }
            $tmp = ['交易时间' => $val[0],'摘要' => $val[7],'备注' => $val[2],
                '交易方信息'=>$val[5],'交易方账号'=>$val[6],'账户余额'=>$val[4]];

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