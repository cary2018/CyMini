<?php
declare (strict_types = 1);

namespace app\api\controller;

class Index
{
    public function index()
    {
        $nav['list'] = GetCache('NavMenu');
        $nav['webInfo'] = CfgInfo('web');
        echo json_encode($nav,JSON_UNESCAPED_UNICODE);
    }
    public function webInfo(){
        $info = CfgInfo('web');
        $info = [
            'web_footer_title'=>$info['web_footer_title'],
            'web_Copy'=>$info['web_Copy'],
            'web_Copyright'=>$info['web_Copyright'],
        ];
        echo json_encode($info,JSON_UNESCAPED_UNICODE);
    }
}
