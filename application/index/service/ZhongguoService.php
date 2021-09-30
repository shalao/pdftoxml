<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 26/9/2021
 * Time: 下午5:47
 */

namespace app\index\service;


/*
 * 中国银行
 */
class ZhongguoService  extends  ConversionService
{
    public function map($res) {
        $list = [];
        foreach ( $res as $key => $val) {
            if (!isset($val[10])) {
                continue;
            }

            $val[3] =str_replace(',', '', $val[3]);
            $val[4] = str_replace(',', '', $val[4]);
            if (!is_numeric($val[3]) || ! is_numeric($val[4]) ) {
                continue;
            }
            $tmp = ['交易时间' => $val[0],'备注' => $val[7],'交易类型'=> $val[6],
                '交易方信息'=>$val[9],'交易方账号'=>$val[10],'账户余额'=>$val[4]];

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