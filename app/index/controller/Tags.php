<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/09/27 23:49
 * file name : Tags.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\index\controller;


use app\BaseController;

class Tags extends BaseController
{
    public function index(){
        return View();
    }
    public function img(){
        $url = 'https://mmbiz.qpic.cn/mmbiz_gif/IP70Vic417DOjMRrV3RrW2HaXlrTFpEI1EYiaJaJkfYOvEuQc3F6RibYXS9oMIvvJNCywron0Wd809g43gmTHlqEA/640?wx_fmt=gif';
        $url = 'https://mmbiz.qpic.cn/mmbiz_png/QFzRdz9libEbdeCBQrQwSy4UO7guTAUAhEMKaJqXzXzww4yibuvqQYGCt0JRCGkdoOPYeyiblT42Uv8uYZM1voicAA/640?wx_fmt=png&from=appmsg';
        //$url = 'https://www.phpernote.com/images/aliyun201911112.jpg';
        echo '<pre>';
        $urlarr = (parse_url($url));
        /*parse_str($urlarr['query'],$parr);
        //print_r($urlarr);
        print_r($parr);*/

        $info = pathinfo($url);
        $weurl = parse_url($url);
        if(isset($weurl['host']) && $weurl['host'] == 'mmbiz.qpic.cn'){
            if(isset($weurl['query'])){
                parse_str($weurl['query'],$parr);
                //$exp = explode('=',$weurl['query']);
                $exp = $parr['wx_fmt'];
            }else{
                $exp = 'jpg';
            }
            $info['extension'] = $exp;
            $info['basename'] = 'WeChat_'.time().'.'.$info['extension'];
        }
        echo '<pre>';
        print_r($info);
    }
}