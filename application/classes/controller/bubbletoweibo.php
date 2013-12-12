<?php

//记录数据迁移到微博
class Controller_Bubbletoweibo extends Layout_Main {

    public function before() {
	parent::before();
    }

    function action_index() {
	$this->auto_render = FALSE;
	$start_id = Arr::get($_GET, 'start_id', 1);
	$end_id = Arr::get($_GET, 'end_id', 100);

	$bubble = Doctrine_Query::create()
			->from('UserBubble b')
			->where('b.id>=' . $start_id . ' AND b.id<= ' . $end_id)
			->orderBy('b.blow_at ASC')
			->fetchArray();

	echo count($bubble) . '条<br>';

	foreach ($bubble AS $b) {
	    echo $b['id'] . ':';
	    $weiboPost = array();
	    $weiboPost['user_id'] = $b['user_id'];
	    $weiboPost['content'] = $b['content'];
	    $weiboPost['post_at'] = $b['blow_at'];
	    $backData = Model_weibo::post($weiboPost);
	    //错误提示
	    if (is_array($backData)) {
		echo $backData['msg'] . '<br>';
	    } else {

		$weibo_id = $backData;
		//转移评论
		$update_votes = Doctrine_Query::create()
				->update('Comment')
				->set('weibo_id', $weibo_id)
				->where('bubble_id=?', $b['id'])
				->execute();

		//评论数
		$total_comments = Doctrine_Query::create()
				->select('id')
				->from('Comment')
				->where('weibo_id=?', $weibo_id)
				->count();

		//修改评论总数
		 Doctrine_Query::create()
				->update('WeiboContent')
				->set('reply_num', $total_comments)
				->where('id=?', $weibo_id)
				->execute();

		echo ': 转移成功,新鲜事id:'.$weibo_id.'，评论总数:'.$total_comments.'<br>';
	    }
	}
    }

}

?>
