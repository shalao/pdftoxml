<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午4:41
 */

namespace app\index\service;


class ParsingService
{
    /**
     * @param $data
     * outname
     * path
     * type
     * password
     */
    public static function Parsing($data)
    {
        $data['outdir'] = "../uploads/" . date("Ymd")."/";
        //$parsing = new NongyeService($data);
        $class = "app\index\service" .'\\'."{$data['type']}" . 'Service';
        if(! class_exists("{$class}",true))
        {
            $parsing = config('parsing.')['parsing_class'];
            $tip = "暂时不支持" . $parsing[$data['type']] . "类型解析";
            exit($tip);
        }
        $parsing = new $class();
        $parsing->run($data);
    }
}
