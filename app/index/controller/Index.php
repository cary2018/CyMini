<?php
namespace app\index\controller;

use app\index\BaseController;
use think\facade\Db;

class Index extends BaseController
{
    public function index()
    {
        //调用插件勾子
        /*hook('show', ['id'=>1]);
        hook('testhook', ['id'=>1]);
        die;*/
        return View();
    }

    public function hello($name = 'ThinkPHP6')
    {
        echo '<pre>';
        //echo base_path();
        $file = base_path().'admin/data/backup/database/';
        echo $file;
        if(!file_exists($file)){
            mkdir($file,0777,true);
        }
        $data = RandRow("",3);
        echo PHP_EOL;
        echo $name.'-----------';
        $list = Db::query('show table status');
        //print_r($list);
        die;
        $url = 'https://1080api.com/api.php/provide/vod/?ac=videolist';
              //https://1080api.com/api.php/provide/vod/?ac=videolist&t=&pg=3&h=&ids=&wd=
        $param = array(
            't'=>'',
            'pg'=>1,
            'h'=>'',
            'ids'=>'21',
            'wd'=>'',
        );
        $rel = FCurl_post($url,$param,'',30,'GET');

        echo '<pre>';
        print_r(json_decode($rel['output']));
    }

}


