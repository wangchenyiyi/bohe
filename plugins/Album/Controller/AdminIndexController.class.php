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

class AdminIndexController extends PluginController {

    private $adminid;

    function _initialize() {
        $this->adminid = sp_get_current_admin_id(); //获取后台管理员id，可判断是否登录
        if (!empty($adminid)) {
            $this->assign("adminid", $this->adminid);
        } else {
            //TODO no login
        }
    }

    function index() {
        $album_model = D("Album");
        $albums = $album_model->select();
        $photos_model = D('Photos');
        foreach ($albums as &$vo) {
            $vo['photos_count'] = $photos_model->where("album_id={$vo['id']}")->count();
        }
        $this->assign("albums", $albums);

        $this->display(":admin_index");
    }

    function add() {
        $album_model = D("Album");
        if (IS_GET) {
            $id = intval(I("get.id"));
            $album = $album_model->where("id=" . $id)->find();
            $this->assign("album", $album);
            $this->display(":admin_add");
        } else {
            $album['id'] = I("post.id");
            $album['title'] = h(I("post.title"));
            $album['description'] = h(I("post.description"));
            if (empty($album['id']) || !$album['id']) {
                assert($album['id']);
                $res = $album_model->add($album);
            } else {
                $res = $album_model->where("id=" . $album['id'])->save($album);
            }
            if ($res !== false) {
                $this->success("操作成功");
            }
            $this->error("操作失败");
        }
    }

    function delete() {
        $album_model = D("Album");
        $photos_model = D('Photos');
        $id = intval(I("get.id"));
        if (empty($id)) {
            $this->error("至少选择一条记录!");
        }
        $count = $photos_model->where("album_id={$id}")->count(); // 查询满足要求的总记录数
        if ($count > 0) {
            $this->error("请先删除该相册下的照片!");
        }
        $where['id'] = $id;
        if ($album_model->where($where)->delete() !== false) {
            $this->success("操作成功");
        }
        $this->error("删除失败!");
    }

    function doPost() {
        $urls = $_POST["photos_url"];
        $titles = $_POST["photos_title"];
        $descriptions = $_POST["photos_description"];
        $album_id = intval(I("post.album_id"));
        if (!$album_id) {
            $this->error("参数错误！");
        }
        if (empty($urls)) {
            $this->error("对不起，至少上传一张照片!");
        }
        $count = count($urls);
        $photos_model = D('Photos');
        $photos = array();

        for ($i = 0; $i < $count; $i++) {
            $url = sp_asset_relative_url($urls[$i]);
            $name = substr($url, strripos($url, "/")+1);
            list($_name, $ext) = explode('.', $name);
            $photos[] = array(
                'title' => $titles[$i],
                'description' => $descriptions[$i],
                'url' => $url,
                'album_id' => $album_id,
                'user_id' => $this->adminid,
                'name' => $name,
                'ext' => $ext,
                'ctime' => NOW_TIME
            );
            unset($url);
        }
        if ($photos_model->addAll($photos) != false) {
            $this->success("共上传{$count} 张照片成功!");
        }
        $this->error("上传照片失败");
    }

    function settocover() {
        $album_model = D("Album");
        $album = array(
            'id' => I("get.id"),
            'cover' => I("get.url")
        );
        if (empty($album['id']) || !$album['id'] || empty($album['cover'])) {
            $this->error("参数有误");
        }
        $image = new \Think\Image();
        $thumb_path = "/data/upload/cover/{$album[id]}.jpg";
        $image->open(SITE_PATH . "data/upload/" . $album[cover]);
        $image->thumb(260, 175, \Think\Image::IMAGE_THUMB_CENTER)->save(SITE_PATH . $thumb_path);
        $album[cover] = sp_asset_relative_url($thumb_path);
        if ($album_model->where("id={$album['id']}")->save($album) !== false) {
            $this->success("设为相册封面成功!");
        }
        $this->error("设为相册封面失败!");
    }

    function photos() {
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
        $this->display(":admin_photos");
    }

    function photos_remove() {
        $photos_model = D('Photos');
        $id = I('get.id');
        if (empty($id)) {
            $this->error("至少选择一条记录!");
        }
        if (is_numeric($id)) {
            $where['id'] = $id;
        } else {
            $where['id'] = array("in", explode(',', $id));
        }
        $list=$photos_model->field("id,url")->where($where)->select();
        foreach ($list as $vo){
            unlink(SITE_PATH.'data/upload/'.$vo[url]);
        }
        if ($photos_model->where($where)->delete() !== false) {
            $this->success("操作成功");
        }
        $this->error("删除失败!");
    }

    function photos_edit() {
        $photos_model = D('Photos');
        if (IS_GET) {
            $id = I('get.id');
            $photo = $photos_model->where("id=" . $id)->find();

            $this->assign("photo", $photo);
            $this->display(":admin_photo_edit");
        } else {
            $photo['id'] = I("post.id");
            $photo['title'] = h(I("post.title"));
            $photo['description'] = h(I("post.description"));
            if ($photos_model->where("id=" . $photo['id'])->save($photo) !== false) {
                $this->success("保存成功");
            }
            $this->error("保存失败!");
        }
    }

}
