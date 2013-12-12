<?php

/**
  +------------------------------------------------------------------------------
 * 投票模块
  +------------------------------------------------------------------------------
 */
class Controller_Vote extends Layout_Main {

    function before() {
        $this->template = 'layout/news';
        parent::before();
    }

    /**
      +------------------------------------------------------------------------------
     * 投票浏览
      +------------------------------------------------------------------------------
     */
    function action_view() {

        $id = Arr::get($_GET, 'id');
        $action = Arr::get($_GET, 'action');
        $view['action'] = $action;

        $vote = Doctrine_Query::create()
                        ->select('v.*')
                        ->addSelect('(SELECT COUNT(u.id) FROM VoteUser u WHERE u.vote_id=v.id ) AS total_user')
                        ->addSelect('(SELECT SUM(o.votes) FROM VoteOptions o WHERE o.vote_id=v.id ) AS total_votes')
                        ->from('Vote v')
                        ->where('v.id=?', $id)
                        ->addWhere('v.is_closed=?', False)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);


        if (!$vote) {
            $this->request->redirect('main/notFound');
        } else {
            $now_date = date('Y-m-d H:i:s');
            if ($vote['finish_date'] AND $vote['finish_date'] < $now_date) {
                $vote['is_finish'] = True;
            } else {
                $vote['is_finish'] = False;
            }
        }

        $view['vote'] = $vote;

        $view['options'] = Doctrine_Query::create()
                        ->from('VoteOptions')
                        ->where('vote_id=?', $id)
                        ->orderBy('order_num ASC,id DESC')
                        ->fetchArray();

        $view['more_vote'] = Doctrine_Query::create()
                        ->from('Vote')
                        ->where('id <> ?', $id)
                        ->addWhere('is_closed=?', False)
                        ->orderBy('id DESC')
                        ->limit(5)
                        ->fetchArray();

        $view['vote_user'] = null;
        $view['selected_options'] = null;
        $view['vote_user'] = null;

        //用户已经登录
        if ($this->_sess->get('id')) {

            //提交投票
            if ($_POST) {

                $update_votes = Doctrine_Query::create()
                                ->update('VoteOptions')
                                ->set('votes', 'votes +1')
                                ->whereIn('id', Arr::get($_POST, 'option'))
                                ->addWhere('vote_id=?', $id)
                                ->execute();

                $post['user_id'] = $this->_sess->get('id');
                $post['vote_id'] = $id;
                $post['selected_id'] = implode(',', Arr::get($_POST, 'option'));
                $post['create_at'] = date('Y-m-d H:i:s');
                $submit_vote = new VoteUser();
                $submit_vote->fromArray($post);
                $submit_vote->save();
                $this->request->redirect('vote/view?id=' . $id);
            }

            //我的投票
            $view['vote_user'] = Doctrine_Query::create()
                            ->from('VoteUser')
                            ->where('vote_id= ?', $id)
                            ->addWhere('user_id= ?', $this->_sess->get('id'))
                            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);


            //我所投的选项
            if ($view['vote_user']) {
                $view['selected_options'] = Doctrine_Query::create()
                                ->from('VoteOptions')
                                ->whereIn('id', explode(',', $view['vote_user']['selected_id']))
                                ->addWhere('vote_id=?', $id)
                                ->orderBy('order_num ASC')
                                ->fetchArray();
            }
        }

        //能否投票
        $view['is_vote'] = False;
        if ($this->_sess->get('id') AND !$view['vote_user']) {
            $view['is_vote'] = True;
        }

        //能否浏览结构
        $view['is_view'] = False;
        if (!$this->_sess->get('id') OR $action == 'view' OR !$view['vote_user']) {
            $view['is_view'] = True;
        }

        $this->_title($view['vote']['title']);
        $this->_render('_body', $view);
    }

}
