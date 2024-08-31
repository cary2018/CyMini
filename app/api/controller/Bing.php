<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2024/08/12 16:46
 * file name : Bing.php
 * User: asusa
 * Author: Hyy-Cary（优）
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;

class Bing extends BaseController
{
    /**
     *  Bing今日壁纸 第三方
     * 	https://bing.img.run/1920x1080.php
     *  Bing随机壁纸 第三方
     *	https://bing.img.run/rand.php
     *  官方api
     *  https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=8&mkt=zh-CN
     *
     */
    public function index(){
        $img = FCurl_post('https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=8&mkt=zh-CN');
        $obj = json_decode($img['output']);
        $rand = mt_rand(0,7);
        $domain = 'https://www.bing.com';
        $imgUrl = $obj->images[$rand]->url;
        return redirect($domain.$imgUrl);
    }
}