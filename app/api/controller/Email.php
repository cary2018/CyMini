<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2024/08/15 01:23
 * file name : Email.php
 * User: asusa
 * Author: Hyy-Cary（优）
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;

class Email extends BaseController
{
    public function index(){
        $data = request()->param();
        $code = mt_rand(100000,999999);
        SetSe('validaCode',$code);
        SetSe('validaEmail',$data['email']);
        $email = $data['email'];
        $nickname = '方外人';
        $subject = '验证码：'.$code;
        $content = '你的验证码：'.$code;
        SendEmail($email,$nickname,$subject,$content);
    }
}