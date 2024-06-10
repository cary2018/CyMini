<?php
namespace app\index\controller;

use app\index\BaseController;

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


