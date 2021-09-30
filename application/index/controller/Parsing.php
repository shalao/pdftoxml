<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午3:59
 */

namespace app\index\controller;

use app\Base;
use think\Request;
use app\index\service\ParsingService;

class Parsing extends Base
{
    public  $dir = "../uploads";
    /**
     * @param Request $request
     * path 表单名叫path
     * type 文件类型
     */
    public function upload(Request $request) {
        $file = request()->file('path');
        $info = $file->validate(['ext'=>'pdf'])->move( $this->dir);
        if(! $info){
             if ($file->getError() == '上传文件后缀不允许' ) {
                 exit("目前只支持pdf 转化为csv");
             }
             exit($file->getError());
        }
        $params = $request->param();
        $parsing =  config('parsing.');
        $name = $_FILES['path']['name'];

        if (empty($params['type'])) {
            //匹配文件名是否存在关键字
            foreach ($parsing['parsing_class'] as $type => $value) {
                if (strpos($name,$value) ===false) {
                    continue;
                }
                $params['type'] = $type;
                break;
            }
            if (empty($params['type'])) {
                exit("请选择文件的分类:例如：微信、浦发银行");
            }
        }

        if (empty($params['password'])) {
            $params['password'] = null;
        }

        //判断type类型是否正确
        if (! key_exists($params['type'],$parsing['parsing_class'])) {
            exit('选择的文件类型有误。请核实后在试');
        }
        $path = $this->dir . "/" . $info->getSaveName();
        $name = str_replace(strrchr($name, "."),"",$name);
        /*
        $name = $info->getFilename();
        $name = str_replace(strrchr($name, "."),"",$name);
        $path = "../uploads/20210926/1.pdf";
	    $name = "1";
	    $params['type'] = 'Nongye';
        var_dump($path,$name,$params);die;
        */
	   ParsingService::Parsing(['path' => $path,'outname'=>$name,'type' => $params['type'],'password' => $params['password']]);
    }

    public function index(Request $request) {

    }


}
