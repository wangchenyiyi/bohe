<?php

// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

namespace plugins\Album\Controller; //Demo插件英文名，改成你的插件英文就行了

use Api\Controller\PluginController; //插件控制器基类

class IndexController extends PluginController {

    function index() {
        $album_model = D("Album"); //实例化Common模块下的Users模型
        $photos_model = D('Photos');

        $id = I("get.id");

        $album = $album_model->where("id=" . $id)->find();
        $where['album_id'] = $id;

        $count = $photos_model->where($where)->count(); // 查询满足要求的总记录数
        $page = $this->Page($count, 40); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show("Admin"); // 分页显示输出
        $list = $photos_model->where($where)->order("id desc")
                        ->limit($page->firstRow, $page->listRows)->select();

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign("album", $album);

        $this->display(":index");
    }

}
