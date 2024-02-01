<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/11/25 23:41
 * file name : Cache.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */

namespace app\admin\controller;

use app\admin\BaseController;

class Cache extends BaseController
{
    public function index(){
        //删除缓存
        delCache('Menu');
        //删除菜单列表缓存
        delCache('MenuList');
        //更新菜单列表缓存
        caheMenu();
        //更新菜单缓存
        SetMenu();
        //阅读权限
        $str = [
            '待审核',
            '开放浏览'
        ];
        //设置阅读权限缓存
        SetCaChe('readArticle',$str);
        //更新文章属性缓存
        $attr = AllTable('attribute',[['status','=',1]],['orderSort','id'=>'desc']);
        SetCaChe('attribute',$attr);
        //设置前台导航api缓存
        navApi();
        //上网导航缓存
        Navigation();
        //更新行政区域缓存
        AreaList();
        //更新配置文件
        putFile();
        $msg = ['code'=>1,'msg'=>lang('cacheData')];
        echo json_encode($msg);
    }
}