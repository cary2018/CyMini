<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/09/26 18:33
 * file name : Search.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\index\controller;


use app\BaseController;
use think\facade\View;

class Search extends BaseController
{
    public function index(){
        return View::fetch();
    }
}