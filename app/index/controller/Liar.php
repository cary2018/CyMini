<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/06/07 12:17
 * file name : Liar.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\index\controller;


use app\admin\service\ImgCompress;
use app\BaseController;
use think\facade\Cookie;
use think\facade\Db;
use think\facade\View;

class Liar extends BaseController
{
    public function index(){
        $id = request()->param('id');
        if($id){
            $data = FindTable('category',[['id','=',$id],['isShow','=',1]]);
            View::assign('cate',$data);
        }else{
            View::assign('cate');
        }
        return View();
    }

    public function saveAt(){
        $post = request()->param();
        $check = request()->checkToken('__token__', request()->param());
        //更新token
        $token = request()->buildToken('__token__', 'sha1');
        //获取验证码
        $valida = GetSe('validaCode');
        //获取验证邮箱
        $validaEmail = GetSe('validaEmail');
        if(false === $check) {
            //throw new ValidateException('invalid token');
            $arr = ['code'=>300,'message'=>'令牌数据无效','token'=>$token];
        }else{
            if($valida == $post['code'] && $validaEmail == $post['email']){
                unset($post['__token__']);
                unset($post['code']);
                $post['createTime'] = time();
                $post['isShow'] = 1;
                $lid = saveId('liar',$post);
                //显示所有错误
                /*error_reporting(11);
                //提示报错信息
                ini_set('display_errors','On');*/
                //配置内存大小
                //ini_set('memory_limit', '256M');
                $files = UploadImg('thumbImg');
                $arrFile = [];
                foreach ($files['result'] as $v){
                    $img = [
                        'lid'=>$lid,
                        'img'=>$v['img'],
                        'thumbImg'=>$v['thumb'],
                    ];
                    array_push($arrFile,$img);
                }
                batchSave('liarImg',$arrFile);
                $arr = ['code'=>200,'message'=>'上传成功','token'=>$token];
                DelSe('validaCode');
                DelSe('validaEmail');
            }else{
                $arr = ['code'=>300,'message'=>'验证码错误或邮箱号与验证码不匹配！','token'=>$token,'cod'=>$post['code']];
            }
        }
        echo json_encode($arr);
    }

    public function emailCode(){
        $data = request()->param();
        $code = mt_rand(100000,999999);
        SetSe('validaCode',$code);
        SetSe('validaEmail',$data['email']);
        $email = $data['email'];
        $nickname = '6666';
        $subject = '验证码：'.$code;
        $content = '你的验证码：'.$code;
        SendEmail($email,$nickname,$subject,$content);
    }
}