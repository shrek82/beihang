<?php

//名称：论坛控制器
class Controller_Bbs extends Layout_Main {

    function before() {

        $this->template = 'layout/bbs';
        parent::before();
    }

    /**
      +------------------------------------------------------------------------------
     * 论坛主题列表
      +------------------------------------------------------------------------------
     */
    function action_list() {

        $this->userPermissions('bbsView');
        $page_size = 25;
        $params = array();
        //接收参数
        $aa_id = Arr::get($_GET, 'aid');
        $aa_id = $aa_id >= 0 ? $aa_id : null;
        $bbs_id = Arr::get($_GET, 'bid');
        $bbs_id = $bbs_id ? (int) $bbs_id : null;
        $club_id = Arr::get($_GET, 'cid', 0);
        $club_id = $club_id ? (int) $club_id : null;
        $show = Arr::get($_GET, 'show');
        $sid = Arr::get($_GET, 'sid');
        $sid = $sid ? (int) $sid : null;
        $q = Arr::get($_GET, 'q');
        $page = Arr::get($_GET, 'page', 1);

        //默认查询结果
        $view['q'] = $q; //关键字
        $view['aa_info'] = null; //当前校友会信息
        $view['aa_club_info'] = null; //当前校友会下属俱乐部信息
        $view['aa_all_bbs'] = null; //当前指定校友会下属版块
        $view['bbs_info'] = null; //当前版块信息
        $view['aa_id'] = null; //当前校友会id
        $view['bbs_id'] = null; //当前版块id
        $view['show'] = $show;

        //用户加入的校友会id
        $joined_aaids = Model_User::aaIds($this->_uid);

        //1、判断当前所在校友会
        //总会
        if ($aa_id == 0) {
            $params['aid'] = 0;
        }
        //指定某个校友会，查询校友会相关信息
        elseif ($aa_id > 0) {
            $params['aid'] = $aa_id;
            $view['aa_info'] = Db_Aa::getInfoById($aa_id);
            $joined_aaids[] = $aa_id;
        }
        //没有指定校友会
        else {
            $aa_id = null;
        }

        $view['aa_id'] = $aa_id;
        $view['bbs_id'] = $bbs_id;
        $view['club_id'] = $club_id;

        //2、显示论坛顶部校友会列表
        $joined_aaids[] = -1;
        $view['signed_aa'] = Doctrine_Query::create()
                ->select('a.id AS aa_id,a.name AS aa_name, a.sname AS sname')
                ->from('Aa a')
                ->whereIn('a.id', $joined_aaids)
                ->fetchArray();

        //3、如果指定了校友会，查询其下属版块
        if ($aa_id == '0' OR $aa_id > 0) {
            $view['aa_all_bbs'] = Model_Bbs::getBbsByAid($aa_id);
            $aa_bbs_ids = array(0);
            foreach ($view['aa_all_bbs'] as $b) {
                $aa_bbs_ids[] = $b['id'];
            }
        }

        //视图链接附带参数
        $view['params'] = $params;

        //查询条件
        $condition = array(
            'aa_id' => $aa_id,
            'bbs_id' => $bbs_id,
            'club_id' => $club_id,
            'sid' => $sid,
            'q' => $q,
            'show' => $show,
            'page_size' => $page_size,
            'page' => $page,
            'auto_save_cache' => false,
        );

        //查询当前条件论坛总数和列表
        $query_data = Db_Bbs::getUnits($condition);
        $units = $query_data['units'];
        $total_items = $query_data['total_items'];

        $view['units'] = array();
        $view['fixed_units'] = array();
        foreach ($units as $u) {
            if ($u['is_fixed']) {
                $view['fixed_units'][] = $u;
            } else {
                $view['units'][] = $u;
            }
        }

        $view['pager'] = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => $page_size,
                    'view' => 'pager/bbs',
        ));

        $this->_title('交流园地');
        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 查看主题详情
      +------------------------------------------------------------------------------
     */
    function action_viewPost() {

        $this->_custom_media('<script type="text/javascript" src="/static/colorbox/jquery.colorbox-min.js"></script>');
        $this->userPermissions('bbsView');
        $view['point'] = Kohana::config('point')->add;

        $id = Arr::get($_GET, 'id');
        $page = Arr::get($_GET, 'page');
        $reply = Arr::get($_GET, 'reply');
        $action = Arr::get($_GET, 'action');
        $view['action'] = $action;

        //话题详情
        $condition = array(
            'bbs_unit_id' => $id
        );

        $unit = Db_Bbs::getUnitViewData($condition);

        //活动详情
        if ($unit['event_id']) {
            $condition = array(
                'event_id' => $unit['event_id'],
                'aa_info' => True,
                'club_info' => True,
                'album' => True,
                'album_id' => True,
                'photos' => True,
                'signs' => True,
            );
            $view['event_data'] = Db_Event::getEventViewData($condition);
        }

        //查看是否有投票
        $vote = Doctrine_Query::create()
                ->select('v.*')
                ->addSelect('(SELECT COUNT(u.id) FROM VoteUser u WHERE u.vote_id=v.id ) AS total_user')
                ->addSelect('(SELECT SUM(o.votes) FROM VoteOptions o WHERE o.vote_id=v.id ) AS total_votes')
                ->from('Vote v')
                ->where('v.bbs_unit_id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //存在投票
        if ($vote) {
            $view['vote_user'] = null;
            $view['selected_options'] = null;
            $view['vote_user'] = null;
            $view['voteaction'] = Arr::get($_GET, 'voteaction');

            //是否可投票
            $now_date = date('Y-m-d H:i:s');
            if ($vote['finish_date'] AND $vote['finish_date'] < $now_date) {
                $vote['is_finish'] = True;
            } else {
                $vote['is_finish'] = False;
            }

            //提交投票
            if ($action == 'vote' AND $this->_uid) {
                Doctrine_Query::create()
                        ->update('VoteOptions')
                        ->set('votes', 'votes +1')
                        ->whereIn('id', Arr::get($_POST, 'option'))
                        ->addWhere('vote_id=?', $vote['id'])
                        ->execute();

                $optionpost['user_id'] = $this->_uid;
                $optionpost['vote_id'] = $vote['id'];
                $optionpost['selected_id'] = implode(',', Arr::get($_POST, 'option'));
                $optionpost['create_at'] = date('Y-m-d H:i:s');
                $submit_vote = new VoteUser();
                $submit_vote->fromArray($optionpost);
                $submit_vote->save();

                //修改话题最后回复时间以达到上浮功能
                $vote_bbs_unit = Doctrine_Query::create()
                        ->from('BbsUnit')
                        ->where('id = ?', $id)
                        ->fetchOne();
                $vote_bbs_unit['comment_at'] = date('Y-m-d H:i:s');
                $vote_bbs_unit->save();

                $this->request->redirect('bbs/viewPost?id=' . Arr::get($_GET, 'id'));
            }
            //投票选项
            $view['options'] = Doctrine_Query::create()
                    ->from('VoteOptions')
                    ->where('vote_id=?', $vote['id'])
                    ->orderBy('order_num ASC,id DESC')
                    ->fetchArray();

            //我是否已经参与本次投票
            $view['vote_user'] = Doctrine_Query::create()
                    ->from('VoteUser')
                    ->where('vote_id= ?', $vote['id'])
                    ->addWhere('user_id= ?', $this->_uid)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            //我所投的选项
            if ($view['vote_user']) {
                $view['selected_options'] = Doctrine_Query::create()
                        ->from('VoteOptions')
                        ->whereIn('id', explode(',', $view['vote_user']['selected_id']))
                        ->addWhere('vote_id=?', $vote['id'])
                        ->orderBy('order_num ASC')
                        ->fetchArray();
            }
        }

        $view['vote'] = $vote;

        if ($unit) {
            Model_Bbs::unitHit($id);
        } else {
            $this->request->redirect('main/notFound');
            exit;
        }

        //话题编辑、推荐、置顶、删除等权限
        $permission = Db_Bbs::getPermission($unit['id'], $this->_uid, $unit);

        //为模板赋值
        $view['unit'] = $unit;

        //编辑权限
        $view['is_edit_permission'] = $permission['is_edit_permission'];

        //推荐、置顶权限
        $view['is_control_permission'] = $permission['is_control_permission'];

        //首页显示权限
        $view['is_system_permission'] = $permission['is_system_permission'];

        //浏览屏蔽帖子处理
        if ($unit['is_closed']) {
            if (!$permission['is_edit_permission']) {
                echo 'closed!';
                exit;
            }
        }

        $view['page'] = $page;
        $view['reply'] = $reply;
        $this->_title($unit['title']);

        $this->_render('_body', $view);
    }

    /**
      +------------------------------------------------------------------------------
     * 发布主题表单
      +------------------------------------------------------------------------------
     */
    function action_unitForm() {

        $this->userPermissions('bbsPost');

        $user_id = $this->_uid;
        $aa_id = Arr::get($_GET, 'aa_id', 0);
        $club_id = Arr::get($_GET, 'club_id');
        $bbs_id = Arr::get($_GET, 'b');
        $id = Arr::get($_GET, 'id', 0);
        $type = Arr::get($_GET, 'type', 'Post');
        $is_edit_permission = False;
        $is_control_permission = False;
        $is_system_permission = False;
        $unit = null;

        // 情况一、发布新帖，根据判断当前用户是否有权限
        if (empty($id)) {

            if ($this->_setting['prohibit_posting']) {
                $this->deny('暂时关闭新帖发布，请稍候重试，谢谢！');
                exit;
            }

            if ($this->_role != '管理员' && $aa_id > 0) {

                if (!Db_Aa::isMember($aa_id, $user_id)) {
                    $this->deny('您还没有加入到该校友会，暂时不能发帖到该版块！');
                    exit;
                }

                if ($club_id > 0 && !Db_Club::isMember($club_id, $user_id)) {
                    $this->deny('您还不是该俱乐部成员，暂时不能发帖到该版块！');
                    exit;
                }
            }
        }
        //情况二、编辑主题
        else {
            $unit = Doctrine_Query::create()
                    ->select('u.*,b.*,ur.realname AS realname,v.*,p.content,p.hidden')
                    ->from('BbsUnit u')
                    ->leftJoin('u.Bbs b')
                    ->leftJoin('u.Post p')
                    ->leftJoin('u.User ur')
                    ->leftJoin('u.Vote v')
                    ->where('u.id = ?', (int) $id)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

            if ($unit) {
                $permission = Db_Bbs::getPermission($unit['id'], $this->_uid, $unit);
                $is_edit_permission = $permission['is_edit_permission'];
                $is_control_permission = $permission['is_control_permission'];
                $is_system_permission = $permission['is_system_permission'];
                $vote_options = null;

                if (!$is_edit_permission) {
                    $this->deny('非作者本人或不是管理员，没有权限修改。');
                    exit;
                }

                $type = $unit['type'];
                $bbs_id = $unit['bbs_id'];
                //主题其他可选版块
//                $bbs_ids = Model_Bbs::getIDs(array(
//                            'aa_id' => $unit['Bbs']['aa_id'],
//                            'club_id' => $unit['Bbs']['club_id']
//                        ));
                // 是否可以设置只允许组织内成员可见
                $set_limit = ($unit['Bbs']['aa_id'] > 0) ? true : false;

                //存在投票，查询器投票选项
                if ($unit['Vote']) {
                    $vote_options = Doctrine_Query::create()
                            ->from('VoteOptions')
                            ->where('vote_id=?', $unit['Vote']['id'])
                            ->orderBy('order_num ASC,id DESC')
                            ->fetchArray();
                }
            } else {
                $set_limit = ($aa_id) ? true : false;
            }
        }

        //可选版块
        //已加入的校友会id
        $signed_aa_ids = Model_User::aaIds($this->_uid);

        //已加入的俱乐部id
        $signed_club_ids = Model_User::clubIds($this->_uid);

        //指定某一话题
        if ($unit) {
            $signed_aa_ids[] = $unit['aa_id'];
            $signed_club_ids[] = $unit['club_id'];
        }

        //指定了某一个校友会
        if ($aa_id) {
            $signed_aa_ids[] = (int) $aa_id;
        }

        //指定了某一个俱乐部
        if ($club_id) {
            $signed_club_ids[] = (int) $club_id;
        }

        //已加入的校友会
        $signed_aa = DB::select(DB::expr('a.id,a.name'))
                ->from(array('aa', 'a'))
                ->where('id', 'IN', $signed_aa_ids)
                ->execute()
                ->as_array();

        //可选版块下拉列表
        $post_aa = array();

        //添加下拉菜单总会一级分类
        $post_aa[0] = array('id' => '0', 'name' => '校友总会');

        //添加下拉菜单其他校友会一级分类
        foreach ($signed_aa as $a) {
            $post_aa[$a['id']] = array('id' => $a['id'], 'name' => $a['name']);
        }


        //逐个获取每个校友会下属版块id
        foreach ($post_aa as $a) {
            $post_aa[$a['id']]['bbs_ids'] = DB::select(DB::expr('b.id,b.name,b.aa_id,b.club_id'))
                    ->from(array('bbs', 'b'))
                    ->where('b.aa_id', '=', $a['id'])
                    ->where('b.club_id', '=', 0)
                    ->where('b.parent_id', '>=', 0)
                    ->order_by('b.order_num', 'ASC')
                    ->execute()
                    ->as_array();
        }

        //我加入的俱乐部版块
        $signed_club = Doctrine_Query::create()
                ->select('c.id,c.aa_id,c.name')
                ->addSelect('(SELECT b.id FROM Bbs b WHERE b.club_id = c.id LIMIT 1 ORDER BY b.id ASC) AS bbs_id')
                ->from('Club c')
                ->whereIn('id', $signed_club_ids)
                ->fetchArray();

        //添加俱乐部版块到下拉菜单对于的分会下面
        if (count($signed_club) > 0) {
            foreach ($post_aa AS $key => $aid) {
                foreach ($signed_club AS $club) {
                    if ($club['aa_id'] == $aid['id'] AND $club['bbs_id']) {
                        $club_bbs = array('id' => $club['bbs_id'], 'name' => $club['name'], 'aa_id' => $club['aa_id'], 'club_id' => $club['id']);
                        $post_aa[$key]['bbs_ids'][] = $club_bbs;
                    }
                }
            }
        }

        $this->_title('发布主题');
        $this->_render('_body', compact('post_aa', 'vote_options', 'bbs_ids', 'id', 'unit', 'type', 'aa_id', 'bbs_id', 'club_id', 'set_limit', 'is_edit_permission', 'is_control_permission', 'is_system_permission'));
    }

    /**
      +------------------------------------------------------------------------------
     * 发布新主题
      +------------------------------------------------------------------------------
     */
    function action_unitPost() {
        $this->auto_render = false;

        //检测发帖权限
        if (!$this->userPermissions('bbsPost', True)) {
            echo Candy::MARK_ERR . '很抱歉，您暂时还不能发帖！原因：身份未审核。';
            exit;
        }

        if ($_POST) {
            $valid = Validate::setRules($_POST, 'bbs_post');
            $post = Validate::getData();
            $ios = Arr::get($_POST, 'ios');

            if (!$valid->check()) {
                echo Candy::MARK_ERR .
                $valid->outputMsg($valid->errors('validate'));
            } else {
                //是否包含非法关键词
                $filter_str = Model_Filter::check($_POST);
                if ($filter_str) {
                    echo Candy::MARK_ERR . '检测到含有非法关键词“' . $filter_str . '”，请在修改后重试，谢谢！';
                    exit;
                }

                $content = $_POST['content'];
                $con_str=strlen($content);
				if($con_str>65000){
					echo Candy::MARK_ERR . '很抱歉，您发布的内容字符超过65000个字符限制(目前为'.$con_str.')，word文档建议以纯文本粘贴或以附件形式发布，谢谢！';
                    exit;
				}
 
                //来自快速发帖
                if (isset($_POST['quick'])) {
                    $content = nl2br($content);
                }

                //字数限制
                if (strlen($content) < 4) {
                    echo Candy::MARK_ERR . '内容为空或字数太少！';
                    exit;
                }

                //投票贴
                if (isset($post['addvote'])) {
                    $post['is_vote'] = 1;
                }

                //是否包含图片
                preg_match_all("'src[\s\r\n]?=[\s\r\n]?[\\\]?[\'|\"]?(.*?\.(jpg|gif|png))[\\\]?[\'\"]?'si", $content, $imgArray);
                if ($imgArray[1]) {
                    $post['is_pic'] = 1;
                }

                //版块及所属校友会和俱乐部设置
                $bbs_info = Model_Bbs::getBbsByid(Arr::get($_POST, 'bbs_id'));
                if ($bbs_info) {
                    $post['aa_id'] = $bbs_info['aa_id'];
                    $post['club_id'] = $bbs_info['club_id'];
                } else {
                    echo Candy::MARK_ERR . '版块不存在！';
                }

                //属性设置
                $post['type'] = 'Post';
                $post['subject'] = isset($post['subject']) ? $post['subject'] : 1;
                $post['Post']['content'] = $_POST['content'];
                $post['Post']['hidden'] = $post['hidden'];

                //管理属性设置
                if (isset($_POST['is_fixed']) AND isset($_POST['is_good'])) {
                    $post['is_fixed'] = Arr::get($_POST, 'is_fixed') ? TRUE : FALSE;
                    $post['is_good'] = Arr::get($_POST, 'is_good') ? TRUE : FALSE;
                    $post['title_color'] = Arr::get($_POST, 'title_color');
                }

                //修改主题
                if ((int) $post['id'] > 0) {
                    $unit = Doctrine_Query::create()
                            ->from('BbsUnit')
                            ->where('id = ?', $post['id'])
                            ->fetchOne();

                    //作者本人则更新日期
                    if ($unit['user_id'] == $this->_uid) {
                        $post['update_at'] = date('Y-m-d H:i:s');
                    }

                    //存在主题，检查修改权限
                    if ($unit) {
                        //检查操作权限
                        $permission = Db_Bbs::getPermission($unit['id']);
                        if (!$permission['is_edit_permission']) {
                            echo Candy::MARK_ERR . '很抱歉，您没有修改权限！';
                            exit;
                        }

                        //有投票功能
                        if (isset($post['addvote'])) {
                            if (empty($post['vote_id']) AND empty($post['vote_options_textarea'])) {
                                echo Candy::MARK_ERR . '投票选项不能为空！';
                                exit;
                            }
                            if ($post['vote_id'] AND empty($post['vote_opt_title'])) {
                                echo Candy::MARK_ERR . '投票选项不能为空！';
                                exit;
                            }

                            //修改投票
                            if ($post['vote_id']) {
                                $this->updateVote($post, $unit->id);
                            }

                            //增加投票
                            if (!$post['vote_id']) {
                                $this->addVote($post, $unit->id);
                            }
                        }

                        $unit->synchronizeWithArray($post);
                        $unit->save();
                    }

                    if ($ios) {
                        $this->_redirect('bbs/viewPost?id=' . $post['id']);
                    } else {
                        echo $unit['id'];
                    }
                }
                //发布新主题
                else {
                    unset($post['id']);

                    //增加新投票
                    if (isset($post['addvote']) AND empty($post['vote_options_textarea'])) {
                        echo Candy::MARK_ERR . '投票选项不能为空！';
                        exit;
                    }

                    $post['user_id'] = $this->_uid;
                    $post['create_at'] = date('Y-m-d H:i:s');
                    $unit = new BbsUnit();
                    $unit->fromArray($post);
                    $unit->save();
                    Db_User::updatePoint('bbsunit');

                    if ($ios) {
                        $this->_redirect('bbs/viewPost?id=' . $unit->id);
                    } else {
                        echo $unit->id;
                    }

                    //增加新投票
                    if (isset($post['addvote']) AND $post['vote_options_textarea']) {
                        $this->addVote($post, $unit->id);
                    }
                }

                //更新相应话题列表缓存
                if ($bbs_info) {
                    Model_Bbs::updateListCacheByAid($bbs_info['aa_id']);
                }
            }
        }
    }

    //添加投票
    function addVote($post, $unit_id) {
        if ($post['votetype'] AND $post['vote_options_textarea'] AND $unit_id) {
            $vote = new Vote();
            $votepost['bbs_unit_id'] = $unit_id;
            $votepost['title'] = $post['title'];
            $votepost['type'] = $post['votetype'];
            $votepost['max_select'] = $post['max_select'];
            $votepost['user_id'] = $this->_uid;
            $votepost['create_at'] = date('Y-m-d H:i:s');
            $votepost['start_date'] = date('Y-m-d');
            $votepost['finish_date'] = $post['finish_date'] ? $post['finish_date'] : null;
            $vote->fromArray($votepost);
            $vote->save();

            if (isset($post['vote_options_textarea'])) {
                $array_option = explode("\n", trim($post['vote_options_textarea']));
            } elseif (isset($post['vote_opt_title'])) {
                $array_option = $post['vote_opt_title'];
            } else {
                //echo Candy::MARK_ERR . '很抱歉，您没有修改权限！';
                //exit;
            }

            if ($array_option) {
                $options = new Doctrine_Collection('VoteOptions');
                foreach ($array_option as $i => $value) {
                    if (trim($value)) {
                        $options[$i]->title = Text::limit_chars(trim($value), 40);
                        $options[$i]->vote_id = $vote->id;
                        $options[$i]->votes = 0;
                        $options[$i]->order_num = $i + 1;
                    }
                }
                $options->save();
            }
        }
    }

    //修改投票
    function updateVote($post, $unit_id) {
        $vote = Doctrine_Query::create()
                ->from('Vote')
                ->where('bbs_unit_id= ?', $unit_id)
                ->fetchOne();
        if ($vote) {
            $vote['type'] = $post['votetype'];
            $vote['max_select'] = $post['max_select'];
            $vote['finish_date'] = $post['finish_date'] ? $post['finish_date'] : null;
            $vote->save();

            foreach ($post['vote_opt_id'] as $key => $opt_id) {
                //修改
                if ((int) $opt_id > 0) {
                    $vote_options = Doctrine_Query::create()
                            ->from('VoteOptions')
                            ->where('id=?', $opt_id)
                            ->fetchOne();
                    $vote_options['order_num'] = $post['vote_opt_order_num'][$key];
                    $vote_options['title'] = $post['vote_opt_title'][$key];
                    $vote_options->save();
                }
                //追加一项
                elseif (trim($post['vote_opt_title'][$key])) {
                    $vote_options = new VoteOptions();
                    $apt['vote_id'] = $vote->id;
                    $apt['title'] = Text::limit_chars(trim($post['vote_opt_title'][$key]), 40);
                    $apt['order_num'] = $post['vote_opt_order_num'][$key] ? $post['vote_opt_order_num'][$key] : $key + 1;
                    $apt['votes'] = 0;
                    $vote_options->fromArray($apt);
                    $vote_options->save();
                } else {
                    //not option
                }
            }
        }
    }

    //删除投票选项
    function action_delVoteOpiton() {
        $vote_id = Arr::get($_GET, 'vid');
        $opiton_id = Arr::get($_GET, 'oid');
        $vote = Doctrine_Query::create()
                ->from('Vote')
                ->where('id=?', $vote_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $option = Doctrine_Query::create()
                ->from('VoteOptions o')
                ->where('o.id= ?', $opiton_id)
                ->addWhere('o.vote_id= ?', $vote_id)
                ->count();

        if (($vote['user_id'] == $this->_uid OR $this->_role == '管理员') AND $option > 0) {
            Doctrine_Query::create()
                    ->delete('VoteOptions')
                    ->where('id= ?', $opiton_id)
                    ->execute();
        }
    }

    //主题评论
    function action_comment() {
        $this->auto_render = FALSE;
        $page = Arr::get($_GET, 'page');
        $bbs_unit_id = Arr::get($_GET, 'bbs_unit_id');
        $cmt_id = Arr::get($_GET, 'cmt_id');
        $reply = Arr::get($_GET, 'reply');
        $alive = time() - 900;
        $pagesize = 20;

        //所有评论
        $comments = DB::select('id')
                ->from('comment')
                ->where('bbs_unit_id', '= ', (int) $bbs_unit_id)
                ->order_by('id', 'ASC')
                ->execute()
                ->as_array();

        $floor = array();
        foreach ($comments AS $key => $c) {
            $floor['floor_' . $c['id']] = $key + 1;
        }

        //当前页
        $comment = DB::select(DB::expr('c.*,u.realname,u.sex,u.start_year,u.city,u.speciality,u.point,u.homepage,u.bbs_unit_num,u.reg_at,u.intro'))
                ->select(DB::expr('(SELECT o.id FROM ol o WHERE o.uid=c.user_id AND o.time>' . $alive . ' ) AS online'))
                ->from(array('comment', 'c'))
                ->join(array('user', 'u'))
                ->on('u.id', '=', 'c.user_id')
                ->where('c.bbs_unit_id', '= ', (int) $bbs_unit_id);

        if ($reply > 0) {
            $comment = $comment->where('c.user_id', '= ', $reply);
        }

        //只显示1条
        if ($cmt_id) {
            $comment = $comment->where('c.id', '= ', $cmt_id);
            $pagesize = 1;
            $comments = 1;
        }

        $total_items = count($comments);
        $pager = Pagination::factory(array(
                    'total_items' => $total_items,
                    'items_per_page' => $pagesize,
                    'view' => 'inc/pager/comment',
        ));

        $page = $page ? $page : $pager->total_pages;

        //自动跳转到最后一页
        if (Arr::get($_GET, 'page') == 'last') {
            $pager->set_current_page($pager->total_pages);
        }

        $comments = $comment->offset($pager->offset)
                ->order_by('c.id', 'ASC')
                ->limit($pager->items_per_page)
                ->execute()
                ->as_array();

        echo View::factory('bbs/comment', compact('cmt_id', 'pager', 'comments', 'bbs_unit_id', 'reply', 'floor'));
    }

    //屏蔽话题(删除由总会后台执行)
    function action_close() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $unit = Doctrine_Query::create()
                ->from('BbsUnit')
                ->where('id =?', $cid)
                ->fetchOne();

        if ($unit) {
            //操作权限
            $permission = Db_Bbs::getPermission($unit['id']);
            $is_control_permission = $permission['is_control_permission'];
            if ($is_control_permission) {
                $unit->is_closed = TRUE;
                $unit->save();
            }

            //操作日志
            if ($this->_role == '管理员') {
                $log_data = array();
                $log_data['type'] = '话题管理';
                $log_data['bbs_unit_id'] = $cid;
                $log_data['description'] = '屏蔽话题“' . $unit['title'] . '”';
                Common_Log::add($log_data);
            }
        }
    }

    //设置主题推荐或置顶属性
    function action_setProperty() {
        $this->auto_render = FALSE;
        $cid = Arr::get($_GET, 'cid');
        $property = Arr::get($_GET, 'property');

        $unit = Doctrine_Query::create()
                ->from('BbsUnit')
                ->where('id = ?', $cid)
                ->fetchOne();

        $permission = Db_Bbs::getPermission($unit['id']);
        if ($permission['is_control_permission']) {
            if ($unit[$property] == True) {
                $post[$property] = False;
            } else {
                $post[$property] = True;
                //奖励积分
                if ($property == 'is_good') {
                    Db_User::updatePoint('good_unit', true, $unit['user_id']);
                }
            }
            $unit->fromArray($post);
            $unit->save();
            //更新列表缓存
            Model_Bbs::updateListCacheByAid($unit['aa_id']);
        } else {
            echo '很抱歉，您没有权限进行此项设置!';
        }
    }

    //更新某版块最后发布或回复时间
    function updateLastReplyTime($create_at, $comment_at) {
        $lastUpdateTime = $this->_sess->get('lastUpdateTime');
        $time = strtotime($create_at) > strtotime($comment_at) ? $create_at : $comment_at;
        if (strtotime($time) > strtotime($lastUpdateTime)) {
            $this->_sess->set('lastUpdateTime', $time);
        }
    }

    //查询某一发布或回复日期之后的帖子总数
    function action_lastUpdateNum() {
        $this->auto_render = FALSE;
        $lastUpdateTime = $this->_sess->get('lastUpdateTime');
        $count = 0;
        if ($lastUpdateTime) {
            $count = Doctrine_Query::create()
                    ->select('id')
                    ->from('BbsUnit')
                    ->where('create_at>"' . $lastUpdateTime . '"')
                    ->addWhere('comment_at>"' . $lastUpdateTime . '"')
                    ->getSqlQuery();
        }
        echo $count;
    }

    /**
      +------------------------------------------------------------------------------
     * 论坛首页
      +------------------------------------------------------------------------------
     */
    function action_index() {

        $from = Arr::get($_GET, 'f', 0);
        $view['from'] = $from;
        $params = explode('_', $from);
        $user_id = $this->_uid;

        // 登录后只显示已加入的校友会论坛
        $aa = Doctrine_Query::create()
                ->select('a.name, c.name')
                ->from('Aa a');
        if ($user_id) {
            $aa->whereIn('a.id', Model_User::aaIds($user_id));
        }
        $aa = $aa->fetchArray();

        $view['aa'] = $aa;
        $view['aa_id'] = $params[0]; //组织id
        //当前俱乐部id
        if (count($params) == 2) {
            $view['club_id'] = $params[1];
        } else {
            $view['club_id'] = 0;
        }


        //当前论坛名称
        if (!$from) {
            $view['bbs_info'] = array('id' => $view['aa_id'], 'name' => $this->_config->base['alumni_name'] . '公共论坛');
        } else {
            $view['bbs_info'] = Doctrine_Query::create()
                    ->select('a.id,a.name')
                    ->from('Aa a')
                    ->where('a.id=?', $view['aa_id'])
                    ->fetchOne();
        }

        // 一周热点主题
        $span_time = date('Y-m-d H:i:s', time() - Date::WEEK);

        $hot_topic = Doctrine_Query::create()
                ->select('u.id,u.bbs_id,u.title,u.create_at,u.user_id,u.type,b.aa_id')
                ->from('BbsUnit u')
                ->leftJoin('u.Bbs b')
                ->addSelect('(SELECT a.name FROM Aa a WHERE a.id=b.aa_id) AS aa_city')
                ->addSelect('(SELECT us.realname FROM User us WHERE us.id=u.user_id) AS user_name')
                ->where('u.is_closed = ?', FALSE)
                ->andWhere('u.create_at > ?', date('Y-m-d H:i:s', (time() - Date::WEEK)))
                ->orderBy('u.hit DESC, u.is_good DESC,
                            case when u.comment_at IS NOT NULL then u.comment_at
                                 when u.comment_at IS NULL then u.create_at
                            end DESC')
                ->limit(10)
                //->useResultCache(true, 360, 'hot_topic' . $from)
                ->fetchArray();
        // $view['hot_topic'] = $hot_topic->useResultCache(true, 300, 'hot_topic')
        //->fetchArray();

        $view['hot_topic'] = $hot_topic;

        // 焦点(图片)新闻
        $view['bbs_focus'] = Doctrine_Query::create()
                ->select('u.id,u.title,u.img_path,u.type')
                ->from('BbsUnit u')
                ->where('u.is_focus=?', True)
                ->orderBy('u.id DESC')
                ->limit(5)
                ->fetchArray();

        //今日开始时间
        $today_time = date('Y-m-d');

        //总会下的所有版块大类
        if (!$from) {
            $parent_bbs = Doctrine_Query::create()
                    ->select('p.*')
                    ->from('Bbs p')
                    ->where('p.aa_id=0 AND p.parent_id=-1')
                    ->orderBy('p.order_num ASC')
                    ->fetchArray();

            foreach ($parent_bbs as $key => $p) {
                $parent_bbs[$key]['bbs_total'] = Doctrine_Query::create()
                        ->from('Bbs')
                        ->where('parent_id=?', $p['id'])
                        ->count();
            }
            $view['parent_bbs'] = $parent_bbs;
        }

        //该组织下属版块总数
        $total_aa_bbs = Doctrine_Query::create()
                ->from('Bbs')
                ->where('aa_id=' . $params[0])
                ->count();

        //暂无版块
        if (!$total_aa_bbs) {
            $aa_bbs = new Bbs();
            $post['aa_id'] = $params[0];
            $post['parent_id'] = 0;
            $post['name'] = '公共论坛';
            $post['intro'] = '公共交流版块';
            $post['order_num'] = 1;
            $aa_bbs->fromArray($post);
            $aa_bbs->save();
        }

        //该组织下属版块
        $aa_bbs = Doctrine_Query::create()
                ->select('b.id,b.name,b.aa_id,b.club_id,b.parent_id,b.intro')
                ->from('Bbs b')
                ->addSelect('(SELECT COUNT(cun.id) FROM BbsUnit cun WHERE cun.bbs_id=b.id and cun.is_closed=False ) AS un_count')//版块主题总数
                ->addSelect('(SELECT COUNT(tun.id) FROM BbsUnit tun WHERE  tun.bbs_id=b.id AND tun.create_at>=curdate() AND tun.is_closed=False ) AS today_count') //今日主题数
                ->addSelect('(SELECT SUM(run.reply_num) FROM BbsUnit run WHERE  run.bbs_id=b.id ) AS reply_count') //版块所有回帖总数
                ->where('b.aa_id=' . $params[0] . ' AND b.club_id=' . $view['club_id']);
        if (!$from) {
            $aa_bbs = $aa_bbs->andWhere('b.parent_id>0');
        }
        $aa_bbs = $aa_bbs->orderBy('b.id ASC')
                ->fetchArray();
        $view['aa_bbs'] = $aa_bbs;


        //下属版块最主题
        $aa_bbs_unit = array();
        foreach ($aa_bbs as $b) {
            $aa_bbs_unit[$b['id']] = Doctrine_Query::create()
                    ->select('un.id,un.title,un.create_at,un.comment_at,u.id,u.realname')
                    ->from('BbsUnit un')
                    ->leftJoin('un.User u')
                    ->where('un.is_closed = ?', FALSE)
                    ->andWhere('un.bbs_id=?', $b['id'])
                    ->orderBy('case when un.comment_at IS NOT NULL then un.comment_at
                 when un.comment_at IS NULL then un.create_at
            end DESC')
                    ->limit(1)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }

        $view['aa_bbs_unit'] = $aa_bbs_unit;


        // 目前在线校友（文字链接）
        $online_ids = array_keys(Model_User::online());
        $user_on = Doctrine_Query::create()
                ->select('u.realname')
                ->from('User u')
                ->whereIn('u.id', $online_ids)
                ->orderBy('u.login_time DESC')
                ->useResultCache(true, 300, 'user_online')
                ->fetchArray();
        $view['online'] = $user_on;

        $this->_title('交流园地');
        $this->_render('_body', $view);
    }

}

?>