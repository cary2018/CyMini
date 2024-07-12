<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/03/27 00:01
 * file name : Welcome.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\admin\controller;


use app\admin\BaseController;
use think\facade\Db;
use think\facade\View;

class Welcome extends BaseController
{
    public function index(){
        $today_start=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $today_end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $where = [['createTime','between',[$today_start,$today_end]]];
        $todayCount = CountTable('visit',$where);
        $views =  CountTable('visit');
        $article =  CountTable('article');
        $nav =  CountTable('navigation');
        $feed =  CountTable('feedback');
        $sql = 'SELECT VERSION() AS version';
        $version = Db::query($sql);
        $mysql = $version[0]['version'];
        $arr = [
            'v'=>GetConfig('version','code'),
            'h'=>GetConfig('version','host'),
            'ip'=>get_client_ip(),
        ];
        $update = FCurl_post(GetConfig('version','domain'),$arr);
        if($update['response_code'] == 200){
            $update = json_decode($update['output'],true);
        }else{
            $update = ['code'=>'400','msg'=>lang('update_detection_failed')];
        }
        View::assign('today',$todayCount);
        View::assign('views',$views);
        View::assign('article',$article);
        View::assign('nav',$nav);
        View::assign('feed',$feed);
        View::assign('mysql',$mysql);
        View::assign('update',$update);
        return View();
    }
}

