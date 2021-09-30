<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 29/9/2021
 * Time: 下午5:32
 */

namespace app\index\service;
use think\exception\ValidateException;
use think\facade\Log;

class ThirdPartyPdfService
{
    public $convert_host = "https://pdf2doc.ali.duhuitech.com";
    public $progress_host = "https://apitx.duhuitech.com";
    public $appcode = '';
    public $token = '';

    public function __construct($pdf)
    {
        $this->appcode =  config('parsing.appcode');
        $path = "/v1/convert";
        $headers = [];
        array_push($headers, "Authorization:APPCODE " . $this->appcode);
        $querys = "url={$pdf}&type=xlsx&ocr=0&language=2";
        $bodys = "";
        $uri =  $path . "?" . $querys;
        $str = get($this->convert_host,$uri,$bodys,$headers);
        $res = json_decode($str,true);
        if (empty($res['result']['token'])) {
            throw new ValidateException(__FUNCTION__. '解析错误:错误内容'.$res['msg']);
        }
        $this->token = $res['result']['token'];

    }


    /**
     *
     * @param $savePath
     * {"code":10000,"msg":"","result":{"progress":0.81,"status":"Doing"},"token":"932b0f3daa67878d033f2bb21f4668900"}
     * {"code":10000,"msg":"","result":{"fileurl":"https://file.duhuitech.com/o/932b0f3daa67878d033f2bb21f4668900/6b8f5f92-ec4a-4f1b-b00c-78cb31e1d4c1.xlsx","status":"Done"},"token":"932b0f3daa67878d033f2bb21f4668900"}
     *
     **/
    public function progressRate($savePath) {

       $uri = "/q?token=" . $this->token;

        while (true) {
            sleep(3);
            $str = get($this->progress_host,$uri);
            $res = json_decode($str,true);
            if (isset($res['code']) && $res['code'] != 10000) {
                throw new ValidateException(__FUNCTION__. '解析错误:错误内容'.$res['msg']);
            }
            if (! empty($res['result']['status']) && strtolower($res['result']['status']) == "done") {
                break;
            }
            Log::record(__FUNCTION__ . "request time :" . date("Y-m-d H:i:s") . "\n" . "response info :".$str . "\n");
        }
        if (empty($res['result']['fileurl'])) {
            throw new ValidateException(__FUNCTION__. '解析错误');
        }
        Log::record(__FUNCTION__ . "save path " . $savePath. "\n");
        curlDownload($res['result']['fileurl'],$savePath);
    }



}