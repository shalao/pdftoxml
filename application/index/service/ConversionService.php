<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/9/2021
 * Time: 下午2:04
 */
namespace app\index\service;
use PhpOffice\PhpSpreadsheet\IOFactory;
use think\exception\ValidateException;

abstract class ConversionService
{
    public $outname;
    public $path;
    public $outdir;
    public $password = null;
    public $outfile ;

    /**
     * @return mixed
     * pdf 解析
     */
    public  function escape() {
        $analyze = new PdfAnalyzeService($this->path);
        $name = uniqid();
        $params['format'] = 'csv';
        $params['outfile'] = $this->outdir . $name . ".csv";
        $this->outfile = $params['outfile'];
        if (! empty($this->password)) {
            $params['password'] = $this->password;
        }
        $analyze->analyse($params);
    }
    /*
     * 读csv 内容
     */
    public function extract()
    {
//        $reader = IOFactory::createReader("Csv");
        $reader = IOFactory::createReaderForFile($this->outfile);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($this->outfile);
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($sheet->getRowIterator(1) as $row) {
            $tmp = [];
            foreach ($row->getCellIterator() as $cell) {
                $tmp[] = $cell->getFormattedValue();
            }
            $res[$row->getRowIndex()] = $tmp;
        }
        $list = $this->map($res);
        ExportService::export($list,$this->outname);
    }

    abstract  function map($list);

    public function setConfig($data) {
        if (! isset($data['outname'],$data['path'],$data['outdir'])) {
            throw new ValidateException(__FUNCTION__. '配置有误');
        }
        $this->outname = $data['outname'];
        $this->path = $data['path'];
        $this->outdir = $data['outdir'];
        if (! empty($data['password'])) {
            $this->password = $data['password'];
        }
    }

    public function run($data) {
        $this->setConfig($data);
        $this->escape();
        $this->extract();
    }
}