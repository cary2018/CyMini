<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/09/30 14:27
 * file name : Feedback.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\api\controller;


use app\api\BaseController;

class Feedback extends BaseController
{
    public function index(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $aid = $data['aid']?$data['aid']:'';
        $sortId = $data['cate']?$data['cate']:'';
        $where = [['status','=',1]];
        if($aid){
            $where[] = ['aid','=',$aid];
        }
        if($sortId){
            $where[] = ['cate','=',$sortId];
        }
        $list = pageTable('feedback',$start,$size,$where);
        $count = CountTable('feedback',$where);
        foreach ($list as &$v){
            $rep = FindTable('feedback',['id'=>$v['rid']]);
            $v['date'] = date('Y-m-d H:i:s',$v['createTime']);
            $v['createTime'] = date('Y-m-d',$v['createTime']);
            $v['lv'] = $v['id'];
            $v['rep'] = $rep['username'];
        }
        $arr = array('code'=>200,'msg'=>'ok','total'=>$count,'data'=>$list);
        echo json_encode($arr);
    }
    public function datalist(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $aid = $data['aid']?$data['aid']:'';
        $sortId = $data['cate']?$data['cate']:'';
        $where = [['status','=',1]];
        if($aid){
            $where[] = ['aid','=',$aid];
        }
        if($sortId){
            $where[] = ['cate','=',$sortId];
        }
        $list = pageTable('feedback',$start,$size,$where);
        $count = CountTable('feedback',$where);
        foreach ($list as &$v){
            $v['date'] = date('Y-m-d H:i:s',$v['createTime']);
            $v['createTime'] = date('Y-m-d',$v['createTime']);
        }
        $arr = array('code'=>200,'msg'=>'ok','total'=>$count,'data'=>$list);
        echo json_encode($arr);
    }
    public function saveAt(){
        $post = request()->param();
        // 检测输入的验证码是否正确
        if(!captcha_check($post['captcha'])){
            // 验证失败
            $arr = ['code'=>300,'message'=>'验证码错误！'];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        };
        $check = request()->checkToken('__token__', request()->param());
        //更新token
        $token = request()->buildToken('__token__', 'sha1');
        if(false === $check) {
            //throw new ValidateException('invalid token');
            $arr = ['code'=>300,'message'=>'令牌数据无效','token'=>$token];
        }else{
            unset($post['__token__']);
            unset($post['captcha']);
            $post['ip'] = get_client_ip();
            $post['createTime'] = time();
            SaveAt('feedback',$post);
            $arr = ['code'=>200,'message'=>'你的评论已进入审核过程。','token'=>$token];
        }
        return json_encode($arr,JSON_UNESCAPED_UNICODE);
    }
}