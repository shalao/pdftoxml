<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午3:40
 */

namespace app\index\service;
use  Tabula\Tabula;


class PdfAnalyzeService
{
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * $data [
     *   'dir' => '带解析文件',
     *   'outdir' => '',
     * ];
     *
     */
    public function analyse($params = [])
    {
        $tabula = new Tabula('/usr/bin/');
        $options = [
            'pages' => 'all',
            'lattice' => true,
            'stream' => true,
        ];
        if (!empty($params)) {
            $options = array_merge( $options,$params);
        }
        $tabula->setPdf($this->path)->setOptions($options)->convert();
    }
}
