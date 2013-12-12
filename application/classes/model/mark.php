<?php
/**
  +-----------------------------------------------------------------
 * 名称：关注模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */

class Model_Mark {

    public static function arr($field, $user_id) {
	$mark = Doctrine_Query::create()
			->select($field)
			->from('UserMark')
			->where('user_id = ?', $user_id)
			->andWhere($field . ' IS NOT NULL')
			->orderBy('mark_at DESC')
			->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

	$marks = array();
	if (!$mark) {
	    $marks = array(0);
	} else {
	    $marks = is_string($mark) ? array($mark) : $mark;
	}

	return $marks;
    }

    public static function status($field, $val) {
	$user_id = Session::instance()->get('id');
	$mark = Doctrine_Query::create()
			->from('UserMark')
			->where($field . ' = ?', $val)
			->andWhere('user_id = ?', $user_id)
			->fetchOne();

	if ($mark) {
	    return '取消关注';
	} else {
	    return '关注';
	}
    }

    //是否被关注(用户，被关注用户)
    public static function is_mark($user_id, $mark_id) {
	$is_mark = Doctrine_Query::create()
			->from('UserMark um')
			->where('um.user_id = ?', $user_id)
			->andWhere('um.user = ?', $mark_id)
			->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
	if ($is_mark) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }


}