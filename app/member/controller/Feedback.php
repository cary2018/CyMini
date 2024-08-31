<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2024/08/16 19:59
 * file name : Feedback.php
 * User: asusa
 * Author: Hyy-Cary（优）
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\member\controller;


use app\member\BaseController;

class Feedback extends BaseController
{
    public function index(){
        return view();
    }
    public function datalist(){
        $data = request()->param();
        $size = $data['limit']?$data['limit']:10;
        $start = $data['page']?$data['page']:0;
        $member = GetSe('MemberCenter');
        $where = [['uid','=',$member['id']]];
        if(array_key_exists('data',$data)){
            foreach ($data['data'] as $k=>$v){
                $where[] = [$v['name'],'like','%'.$v['value'].'%'];
            }
        }
        $list = pageTable('feedback',$start,$size,$where);
        $count = CountTable('feedback',$where);
        foreach ($list as &$v){
            $cate = FindTable('category',['id'=>$v['cid']]);
            $v['createTime'] = date('Y-m-d H:i:s',$v['createTime']);
            $v['temp_archives'] = $cate['temp_archives'];
        }
        $arr = array('code'=>0,'msg'=>'ok','count'=>$count,'data'=>$list);
        echo json_encode($arr);
    }
}