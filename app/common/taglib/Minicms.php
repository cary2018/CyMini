<?php
/**
 * Created by PhpStorm.
 * CreateTime  : 2024/05/12 00:45
 * file name : Minicms.php
 * User: asusa
 * Author: Hyy-Cary
 * Contact QQ  : 373889161(.)
 * email: 373889161@qq.com
 * WeChat: 18319021313
 */


namespace app\common\taglib;
use think\template\TagLib;

class Minicms extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'page'                => ['attr' => '', 'close' => 0],//非必须属性name
        'widget'              => ['attr' => 'name', 'close' => 1],
        'navigation'          => ['attr' => '', 'close' => 1],//非必须属性nav-id,root,id,class
        'navigationmenu'      => ['attr' => '', 'close' => 1],//root,class
        'navigationfolder'    => ['attr' => '', 'close' => 1],//root,class,dropdown,dropdown-class
        'subnavigation'       => ['attr' => 'parent,root,id,class', 'close' => 1],
        'subnavigationmenu'   => ['attr' => '', 'close' => 1],//root,class
        'subnavigationfolder' => ['attr' => '', 'close' => 1],//root,class,dropdown,dropdown-class
        'links'               => ['attr' => '', 'close' => 1],//非必须属性item
        'slides'              => ['attr' => 'id', 'close' => 1],//非必须属性item
        'noslides'            => ['attr' => 'id', 'close' => 1],
        'captcha'             => ['attr' => 'height,width', 'close' => 0],//非必须属性font-size,length,bg,id
        'hook'                => ['attr' => 'name,param,once', 'close' => 0]
    ];

    /**
     * 分页标签
     */
    public function tagPage($tag, $content)
    {
        $name = isset($tag['name']) ? $tag['name'] : '__PAGE_VAR_NAME__';
        $this->autoBuildVar($name);
        $parse = <<<parse
        <?php
            echo empty({$name})?'':{$name};
        ?>
        parse;

        return $parse;
    }

    /**
     * 友情链接标签
     */
    public function tagLinks($tag, $content)
    {
        $item  = empty($tag['item']) ? 'vo' : $tag['item'];//循环变量名
        $parse = <<<parse
        <?php
            \$__LINKS__ = \app\admin\service\ApiService::links();
        ?>
        <volist name="__LINKS__" id="{$item}">
        {$content}
        </volist>
        parse;

        return $parse;

    }
}