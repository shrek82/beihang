<?php

class Controller_Club_Admin_Theme extends Layout_Clubadmin {

    function before() {
        parent::before();
        $actions = array(
            'club_admin_theme/show/banner' => 'Banner',
            'club_admin_theme/show/logo' => 'LOGO链接',
            'club_admin_theme/index' => '页面配色',
            'club_admin_theme/display' => '内容显示',
        );
        $this->_render('_body_action', compact('actions'), 'club_global/admin_action');
    }

    //模板设置
    function action_index() {

        $theme = Doctrine_Query::create()
                ->from('Theme')
                ->where('club_id = ?', $this->_id)
                ->fetchOne();

        if ($_POST) {
            if (!$theme) {
                $theme = new Theme();
                $theme->club_id = $this->_id;
            }
            $theme['background_image'] = Arr::get($_POST, 'file') ? Arr::get($_POST, 'file') : null;
            $theme['theme'] = Arr::get($_POST, 'theme');
            $theme['usercustom'] = Arr::get($_POST, 'usercustom');
            $theme['background_color'] = Arr::get($_POST, 'background_color');
            $theme->save();
            echo $theme->id;
            exit;
        }

        $view['theme'] = $theme;
        $this->_title('俱乐部主题设置');
        $this->_render('_body', $view);
    }


    # 首页图片展示
    function action_show($format = 'banner') {

        $show_id = Arr::get($_GET, 'show_id', 0);

        $show = Doctrine_Query::create()
                ->from('Banner s')
                ->where('s.club_id = ?', $this->_id)
                ->andWhere('s.id = ?', $show_id)
                ->fetchOne();
        $view['show'] = $show;
        $view['format'] = $format;

        $shows = Doctrine_Query::create()
                ->from('Banner s')
                ->where('s.club_id = ?', $this->_id)
                ->addWhere('format = ?', $format);

        $total_items = $shows->count();

        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => 5,
                    'view' => 'pager/common',
                ));

        $view['pager'] = $pager;
        $view['shows'] = $shows->offset($pager->offset)
                ->orderBy('s.order_num ASC')
                ->limit($pager->items_per_page)
                ->fetchArray();

        $this->_title('首页' . $format);
        $this->_render('_body', $view);
    }

    //保存和修改展示图片
    function action_saveshow() {
        $show_id = Arr::get($_POST, 'show_id', 0);
        $show = Doctrine_Query::create()
                ->from('Banner s')
                ->where('s.club_id = ?', $this->_id)
                ->andWhere('s.id = ?', $show_id)
                ->fetchOne();

        // 提交表单
        if ($_POST) {
            $this->auto_render = false;

            if (!Arr::get($_POST, 'filename')) {
                echo 'err#是不是忘记上传图片了？';
                exit;
            }

            if (!Arr::get($_POST, 'title')) {
                echo 'err#请填写标题或名称';
                exit;
            }

            if (!Arr::get($_POST, 'url')) {
                echo 'err#链接地址不能为空，请检查后重试！';
                exit;
            }

            // 新插入
            if (!$show) {
                $banner = new Banner();
                $banner->club_id = $this->_id;
                $banner->filename = Arr::get($_POST, 'filename');
                $banner->title = Arr::get($_POST, 'title');
                $banner->url = Arr::get($_POST, 'url');
                $banner->format = Arr::get($_POST, 'format');
                $banner->order_num = Arr::get($_POST, 'order_num');
                $banner->is_display = True;
                $banner->save();
                echo $banner->id;
            }
            // 修改
            else {
                $show['format'] = Arr::get($_POST, 'format');
                $show['filename'] = Arr::get($_POST, 'filename');
                $show['title'] = Arr::get($_POST, 'title');
                $show['url'] = Arr::get($_POST, 'url');
                $show['order_num'] = Arr::get($_POST, 'order_num');
                $show->save();
                echo $show->id;
            }
        }
    }

    //删除展示图片
    function action_delshow() {
        $this->auto_render = false;
        $show_id = Arr::get($_GET, 'show_id', 0);
        $show = Doctrine_Query::create()
                ->from('Banner s')
                ->where('s.club_id = ?', $this->_id)
                ->andWhere('s.id = ?', $show_id)
                ->fetchOne();
        if ($show) {
            $show->delete();
        }
    }


    //显示或隐藏
    function action_setHidden() {
        $this->auto_render = false;
        $show_id = Arr::get($_GET, 'show_id', 0);
        $show = Doctrine_Query::create()
                ->from('Banner s')
                ->where('s.club_id = ?', $this->_id)
                ->andWhere('s.id = ?', $show_id)
                ->fetchOne();
        $show['is_display'] = $show['is_display'] ? '0' : '1';
        $show->save();
    }

    //内容显示条目
    function action_display() {

        //创建默认风格
        $theme = Doctrine_Query::create()
                ->from('Theme')
                ->where('club_id = ?', $this->_id)
                ->fetchOne();

        //保存显示内容条数
        if ($_POST) {
            $this->auto_render = False;
            if (!$theme) {
                $theme = new Theme();
                $theme->club_id = $this->_id;
            }
            $theme->banner_limit = Arr::get($_POST, 'banner_limit', '0');
            $theme->news_limit = Arr::get($_POST, 'news_limit');
            $theme->weibo_limit = Arr::get($_POST, 'weibo_limit');
            $theme->event_limit = Arr::get($_POST, 'event_limit');
            $theme->bbsunit_limit = Arr::get($_POST, 'bbsunit_limit');
            $theme->allow_post_weibo = Arr::get($_POST, 'allow_post_weibo');
            $theme->weibo_topic = Arr::get($_POST, 'weibo_topic');
            $theme->save();
            echo $theme->id;
            exit;
        }

        $this->_title('校友会首页显示设置');
        $view['theme'] = $theme;
        $this->_render('_body', $view);
    }

}