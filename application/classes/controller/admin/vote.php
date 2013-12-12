<?php

//其他内容管理
class Controller_Admin_Vote extends Layout_Admin {

    function before() {
	parent::before();
	$leftbar_links = array(
	    'admin_vote/index' => '内容管理',
	    'admin_vote/form' => '添加新内容',
	    'admin_vote/category' => '分类管理',
	);

	$this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    //投票管理
    function action_index() {
	$type = Arr::get($_GET, 'type');
	$q = Arr::get($_GET, 'q');
	$vote = Doctrine_Query::create()
			->select('v.id,v.title,v.create_at,v.finish_date,v.type,u.*')
			->addSelect('(SELECT count(*) FROM VoteUser vu WHERE vu.vote_id=v.id) AS user_number')
			->from('Vote v')
			->leftJoin('v.User u')
			->orderBy('v.id DESC');

	if ($type) {
	    $vote->where('v.type=?', $type);
	}

	if ($q) {
	    $vote->where('title LIKE ?', array('%' . $q . '%'));
	}

	$total_vote = $vote->count();

	$pager = Pagination::factory(array(
		    'total_items' => $total_vote,
		    'items_per_page' => 15,
		    'view' => 'pager/common',
		));

	$view['type'] = $type;
	$view['q'] = $q;
	$view['pager'] = $pager;
	$view['vote'] = $vote->offset($pager->offset)
			->limit($pager->items_per_page)
			->fetchArray();

	$this->_title('内容管理');
	$this->_render('_body', $view);
    }


    //添加或修改内容
    function action_form() {
	$id = Arr::get($_GET, 'id', 0);
	$type = Arr::get($_POST, 'type');
	$view['err'] = '';
	$file_name = date("YmdHis");
	$vote = Doctrine_Query::create()
			->from('Vote')
			->where('id = ?', $id)
			->fetchOne();
	$view['vote'] = $vote;
	if ($_POST) {
	    $valid = Validate::setRules($_POST, 'vote');
	    $post = $valid->getData();
	    if (!$valid->check()) {
		$view['err'] .= $valid->outputMsg($valid->errors('validate'));
	    } else {

		// 添加或修改内容
		if ($vote) {
		    unset($post['id']);
		    $post['user_id'] = $this->_sess->get('id');
		    $vote->synchronizeWithArray($post);
		    $vote->save();
		} else {
		    $vote = new Vote();
		    $post['user_id'] = $this->_sess->get('id');
		    $post['create_at'] = date('Y-m-d H:i:s');
		    $post['start_date'] =Arr::get($_POST,'start_date',null);
		    $post['finish_date'] =Arr::get($_POST,'finish_date',null);
		    $vote->fromArray($post);
		    $vote->save();

		    if (Arr::get($_POST, 'option')) {
			$array_option = explode("\n", trim(Arr::get($_POST, 'option')));
			$options = new Doctrine_Collection('VoteOptions');

			foreach ($array_option as $i => $value) {
			    $options[$i]->title = $value;
			    $options[$i]->vote_id = $vote->id;
			    $options[$i]->votes = 0;
			    $options[$i]->order_num = $i+1;
			}

			$options->save();
		    }
		}

		// 处理完毕后刷新页面
		$this->request->redirect('admin_vote/index?type=' . $type);
	    }
	}

	$this->_render('_body', $view);
    }

    //投票选项
    function action_options() {
	$id=Arr::get($_GET,'id');
	$err='';
	$vote_id=Arr::get($_GET,'vote_id');

	$options = Doctrine_Query::create()
			->from('VoteOptions')
			->where('vote_id=?',$vote_id)
			->orderBy('order_num ASC,id DESC')
			->fetchArray();

	$vote = Doctrine_Query::create()
			->select('v.*')
			->addSelect('(SELECT SUM(o.votes) FROM VoteOptions o WHERE o.vote_id=v.id ) AS total_votes')
			->from('Vote v')
			->where('v.id=?',$vote_id)
			->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

	$option = Doctrine_Query::create()
			->from('VoteOptions')
			->where('id=?',$id)
			->fetchOne();

	if ($_POST) {

	        $post['title']=Arr::get($_POST,'title');
		$post['vote_id']=Arr::get($_POST,'vote_id');
		$post['order_num']=Arr::get($_POST,'order_num');

		// 添加或修改内容
		if ($id AND $option) {
		    $option->fromArray($post);
		    $option->save();
		} else {
		    $option = new VoteOptions();
		    $option->fromArray($post);
		    $option->save();
		}
		$this->request->redirect('admin_vote/options?vote_id=' . $vote['id'].'');

	}

	$this->_title('内容管理');
	$this->_render('_body', compact('err','vote','options','option'));
    }

    //添加或修改内容分类
    function action_category() {
	$id = Arr::get($_GET, 'id', 0);
	$view['err'] = '';
	$category = Doctrine_Query::create()
			->from('voteCategory')
			->where('id = ?', $id)
			->fetchOne();

	$view['category'] = $category;

	if ($_POST) {
	    $valid = Validate::setRules($_POST, 'vote_category');
	    $post = $valid->getData();
	    if (!$valid->check()) {
		$view['err'] .= $valid->outputMsg($valid->errors('validate'));
	    } else {
		// 添加或修改内容
		if ($category) {
		    unset($post['id']);
		    $category->synchronizeWithArray($post);
		    $category->save();
		} else {
		    $category = new voteCategory();
		    $category->fromArray($post);
		    $category->save();
		}

		// 处理完毕后刷新页面
		$this->request->redirect('admin_vote/category');
	    }
	}
	$this->_render('_body', $view);
    }

    #删除内容

    function action_del() {
	$this->auto_render = FALSE;
	$cid = Arr::get($_GET, 'cid');
	$del = Doctrine_Query::create()
			->delete('Vote')
			->where('id =?', $cid)
			->execute();
    }

   //删除选项
function action_delOptions() {
	$this->auto_render = FALSE;
	$cid = Arr::get($_GET, 'cid');
	$del = Doctrine_Query::create()
			->delete('VoteOptions')
			->where('id =?', $cid)
			->execute();
    }

}

?>
