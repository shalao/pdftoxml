<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 28/9/2021
 * Time: 下午4:58
 */

namespace app\index\service;


class ZhifubaoService extends  ConversionService
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
            $val[4] =str_replace(',', '', $val[4]);
            if (!is_numeric($val[4]) ) {
                continue;
            }
            $tmp = ['交易时间' => $val[7],'交易方信息'=>  $val[1],'交易流水号'=>$val[5],'摘要'=>$val[3],'备注'=>$val[2]];
            if ($val[0] =='收入') {
                $tmp['收入'] = trim($val[4],"+");
            }else {
                $tmp['支出'] = trim($val[4],"-");
            }
            $list[] = $tmp;
        }
        return $list;
    }


}