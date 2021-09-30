<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if (function_exists("get")) {
    function get($host,$uri,$body="",$headers=[])
    {
        $url = $host . $uri;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $res = curl_exec($curl);
        return $res;
    }
}

if (function_exists("curlDownload")) {
    /**保存远程文件
     * @param $url 远程路径
     * @param $dir 保存目录
     * @return bool|string 返回执行结果
     */
    function curlDownload($url, $save_path)
    {
        $ch = curl_init($url);
        $fp = fopen($save_path, "wb");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $res;

    }
}

