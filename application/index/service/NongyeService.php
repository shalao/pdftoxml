<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午2:19
 */

namespace app\index\service;
/*
 * 中国农业银行
 */
class NongyeService extends ConversionService
{

    public  function escape() {
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
        foreach ( $res as $key => $val) {
            if (! (is_numeric($val[0]) && is_numeric($val[3])) || ! isset($val[7]) ) {
                continue;
            }
            $tmp = ['交易时间' => $val[0],'摘要'=> $val[2],'备注' => $val[7],'交易类型'=> $val[6],'交易方信息'=>$val[5],'账户余额'=>$val[4]];

            if ($val[3] >=0) {
                $tmp['收入'] = trim($val[3],"+");

            }else {
                $tmp['支出'] = trim($val[3],"+");

            }
            $list[] = $tmp;
        }
        return $list;
    }


}
