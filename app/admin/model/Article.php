<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2023/04/20 16:18
 * file name : Article.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\admin\model;


use think\Model;

class Article extends Model
{
    public function dataSave($data){
        $data['updateTime'] = time();
        $soft = ['name'=>$data['name'],'url'=>$data['url'],'code'=>$data['code'],'sort'=>$data['sort']];
        unset($data['name']);
        unset($data['url']);
        unset($data['code']);
        unset($data['sort']);
        if(array_key_exists('attrId',$data)){
            $data['attrId'] = implode(',',$data['attrId']);
        }else{
            $data['attrId'] = '';
        }
        if($data['id'] == ''){
            $data['createTime'] = time();
            unset($data['id']);
            $userInfo = GetSe('admin');
            $data['aid'] = $userInfo['id'];
            $uid = saveId('article',$data);
            $soft['aid'] = $uid;
            //保存下载地址
            $this->batchDown($soft);
            $this->addTags(['tag'=>$data['tags'],'cid'=>$data['cid']]);
        }else{
            if(array_key_exists('articleImg',$data)){
                $img = FindTable('article',[['id','=',$data['id']]]);
                if(file_exists($img['articleImg'])){
                    unlink($img['articleImg']);
                }
                if(file_exists($img['articleThumbImg'])){
                    unlink($img['articleThumbImg']);
                }
            }
            if(array_key_exists('annex',$data)){
                $img = FindTable('article',[['id','=',$data['id']]]);
                if(file_exists($img['annex'])){
                    unlink($img['annex']);
                }
            }
            SaveAt('article',$data);
            $soft['aid'] = $data['id'];
            //保存下载地址
            $this->batchDown($soft);
            $this->addTags(['tag'=>$data['tags'],'cid'=>$data['cid']]);
        }
        $msg = ['code'=>200,'msg'=>lang('success_message'),'data'=>$data];
        return json_encode($msg,JSON_UNESCAPED_UNICODE);
    }
    public function batchDown($data){
        $arr = [];
        foreach ($data['name'] as $k=>$v){
            if($v || $data['url'][$k]){
                $da = [
                    'aid'=>$data['aid'],
                    'name'=>$v,
                    'url'=>$data['url'][$k],
                    'code'=>$data['code'][$k],
                    'orderSort'=>$data['sort'][$k],
                ];
                array_push($arr,$da);
            }
        }
        batchSave('software',$arr);
    }
    public function addTags($tag=[]){
        $exp = explode(',',$tag['tag']);
        $arr = [];
        if($exp){
            foreach ($exp as $v){
                if($v){
                    $data = AllTable('taglist',['tag'=>trim($v)]);
                    if(!$data){
                        $da = [
                            'cid'=>$tag['cid'],
                            'tag'=>$v,
                            'createTime'=>time(),
                        ];
                        array_push($arr,$da);
                    }
                }
            }
            batchSave('taglist',$arr);
        }
    }
}