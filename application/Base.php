<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午4:00
 */
namespace app;
use think\Controller;
use think\Response;

class Base  extends Controller
{
    public function apiError($msg = 'failed',$data = [], $code = 400, $header = [], $options = [])
    {
        $res = ['data' => $data,'msg'=>$msg,'code'=>$code];
        return Response::create($res, 'json', $code, $header, $options);
    }

    public function apiSuccess( $data = [],$msg = 'success',$code = 200, $header = [], $options = [])
    {
        $res = ['data' => $data,'msg'=>$msg,'code'=>$code];
        return Response::create($res, 'json', $code, $header, $options);
    }

}