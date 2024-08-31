<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2024/08/12 21:41
 * file name : Index.php
 * User: asusa
 * Author: Hyy-Cary（优）
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\member\controller;


use app\member\BaseController;

class Index extends BaseController
{
    public function index(){
        return view();
    }
    public function welcome(){
        return view();
    }
}