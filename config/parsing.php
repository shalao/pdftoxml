<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午5:27
 */
return [
    'parsing_class' => [
        'Gongshang' => '工商银行', //Finish
        'Jiaotong' => '交通银行',
        'Minsheng' => '民生银行',  // Finish
        'Pingan' => '平安银行',  //Finish    ocr
        'Pudong' => '上海浦东',  //Finish
        'Weixin' => '微信',       //后面搞
        'Zhaoshang' => '招商银行', //Finish
        'Zhejiangfuyang' => '浙江富阳农村', //Finish   //提取出来的csv,会出现错位，用ocr识别可能会好些。
        'Zheshang' => '浙商银行',     //Finish
        'Zhifubao' => '支付宝',     //不好搞
        'Jianshe' => '建设银行',      //Finish        //提取出来的csv,会出现错位，用ocr识别可能会好些。
        'Nongye' => '中国农业银行',     //Finish
        'Zhongguo' => '中国银行',      //Finish
        'Zhongxin' => '中信银行',     //Finish
    ],
    'host' => '',  //当前请求域名
    'appcode' => '',  //支付宝pdftoxml密钥
];
