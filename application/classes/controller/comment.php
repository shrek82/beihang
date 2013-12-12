<?php
/**
  +-----------------------------------------------------------------
 * 名称：评论控制器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午3:25
 * 相同指数:100%
  +-----------------------------------------------------------------
 */
class Controller_Comment extends Layout_Main {

    /**
      +-----------------------------------------------------------------
     * 评论列表
      +-----------------------------------------------------------------
     */
    function action_list() {
        $this->auto_render = FALSE;
        $search = array();
        if ($_GET) {
            foreach ($_GET as $key => $value) {
                $search[$key] = $value;
            }
        }

        //获取相关查询结果
        $data = Db_Comment::get($search);

        //分页
        $pager = Pagination::factory(array(
                    'total_items' => $data['total_items'],
                    'items_per_page' => $data['limit'],
                    'view' => 'inc/pager/comment',
                ));

        //自动跳转到最后一页
        if (Arr::get($_GET, 'page') == 'last') {
            $pager->set_current_page($pager->total_pages);
        }

        $view['floor'] = $data['floor'];
        $view['comments'] = $data['comments'];
        $view['pager'] = $pager;
        echo View::factory('inc/comment/list', $view);
    }

    function action_deny() {

    }

    //发布评论
    function action_post() {

        $this->auto_render = FALSE;

        if (!$this->userPermissions('comment', True)) {
            echo Candy::MARK_ERR . '很抱歉，您暂时还没有通过审核，暂时还不能发起评论！';
            exit;
        }

        if ($_POST) {
            $redirect = Arr::get($_POST, 'redirect');
            $v = Validate::setRules($_POST, 'comment');
            $post = $v->getData();
            if (!$v->check()) {
                echo Candy::MARK_ERR . $v->outputMsg($v->errors('validate'));
            } else {

                //本身会过滤会让flash无法插入
                $post['content'] = Arr::get($_POST, 'content');

                //是否包含非法关键词
                $filter_str = Model_Filter::check($_POST);

                if ($filter_str) {
                    echo Candy::MARK_ERR . '检测到含有非法关键词“' . $filter_str . '”，请在修改后重试，谢谢！';
                    exit;
                }
                //过滤一些由ueditor编辑器引起的多余换行符
                else {
                    $post['content'] = str_replace('<p>&nbsp;</p>', '', $post['content']);
                }

                //指定评论id是修改操作
                if (isset($post['cmt_id']) AND $post['cmt_id'] > 0) {
                    $comment = Doctrine_Query::create()
                            ->from('Comment')
                            ->where('id = ?', $post['cmt_id'])
                            ->fetchOne();

                    // 管理员身份
                    if ($this->_role == '管理员') {
                        $comment['content'] = $post['content'];
                        $comment->save();
                    }
                    // 本人修改
                    if ($comment['user_id'] == $this->_uid) {
                        $comment['content'] = $post['content'];
                        $comment['update_at'] = date('Y-m-d H:i:s');
                        $comment->save();
                    }
                    echo $comment['id'];
                }

                //发表新评论
                else {
                    $post['user_id'] = $this->_uid;
                    $cid = Model_Comment::post($post);
                    if ($cid) {
                        //更新积分
                        Db_User::updatePoint('comment');
                        //跳转到其他URL
                        if ($redirect) {
                            $this->_redirect($redirect);
                        } else {
                            echo $cid;
                        }
                    } else {
                        echo Candy::MARK_ERR . '评论发表失败！';
                        exit;
                    }
                }
            }
        }
    }

    //修改评论内容
    function action_modifycontent() {
        $this->auto_render = false;
        $id = Arr::get($_GET, 'id');
        $comment = Doctrine_Query::create()
                ->from('Comment')
                ->where('id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        if ($comment) {
            echo $comment['content'];
        }
    }

    //返回记录最新评论
    function action_bubbleList() {
        $id = Arr::get($_GET, 'id');
        $limit = Arr::get($_GET, 'limit', 8);
        $comments = Doctrine_Query::create()
                ->select('c.id,c.user_id,c.content,c.post_at,u.realname AS realname')
                ->from('Comment c')
                ->leftJoin('c.User u')
                ->where('c.bubble_id=?', $id)
                ->limit($limit)
                ->orderBy('c.id DESC')
                ->fetchArray();

        $comments = array_reverse($comments, TRUE);
        $view['comments'] = $comments;
        echo View::factory('inc/comment/bubble', $view);
    }

}