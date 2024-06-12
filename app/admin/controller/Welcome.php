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
        View::assign('today',$todayCount);
        View::assign('views',$views);
        View::assign('article',$article);
        View::assign('nav',$nav);
        View::assign('feed',$feed);
        return View();
    }
}
