<?php

// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

namespace plugins\Album; //Demo插件英文名，改成你的插件英文就行了

use Common\Lib\Plugin;

/**
 * Demo
 */
class AlbumPlugin extends Plugin {//Demo插件英文名，改成你的插件英文就行了

    public $info = array(
        'name' => 'Album', //Demo插件英文名，改成你的插件英文就行了
        'title' => '相册插件',
        'description' => '相册插件',
        'status' => 1,
        'author' => 'Ejoyo',
        'version' => '1.0'
    );
    public $has_admin = 1; //插件是否有后台管理界面

    public function install() {//安装方法必须实现
        $db_pre = C("DB_PREFIX");

        D('')->execute("DROP TABLE IF EXISTS `{$db_pre}album`;
                CREATE TABLE `{$db_pre}album` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(50) DEFAULT NULL,
                  `description` varchar(200) DEFAULT NULL,
                  `cover` varchar(50) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
        D('')->execute("DROP TABLE IF EXISTS `{$db_pre}photos`;
                CREATE TABLE `{$db_pre}photos` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(50) DEFAULT NULL,
                  `alt` varchar(50) DEFAULT NULL,
                  `description` varchar(200) DEFAULT NULL,
                  `url` varchar(100) NOT NULL DEFAULT '',
                  `thumb_url` varchar(100) DEFAULT NULL,
                  `up` int(8) NOT NULL DEFAULT '0',
                  `down` int(8) NOT NULL DEFAULT '0',
                  `album_id` int(11) DEFAULT '0',
                  `user_id` int(11) NOT NULL DEFAULT '0',
                  `ctime` int(11) DEFAULT NULL,
                  `ext` char(6) DEFAULT NULL,
                  `name` varchar(32) DEFAULT NULL,
                  PRIMARY KEY (`id`,`url`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
        return true; //安装成功返回true，失败false
    }

    public function uninstall() {//卸载方法必须实现
         $db_pre = C("DB_PREFIX");
        D('')->execute("DROP TABLE IF EXISTS `{$db_pre}album;"
        . "DROP TABLE IF EXISTS `{$db_pre}photos;");
        return true; //卸载成功返回true，失败false
    }

    //实现的footer钩子方法
    public function footer($param) {
        
    }

}
