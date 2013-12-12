<?php
/**
  +-----------------------------------------------------------------
 * 名称：评论模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午3:43
  +-----------------------------------------------------------------
 */
class Model_Comment {

    //发布评论
    public static function post($data) {
        $data['post_at'] = isset($data['post_at']) ? $data['post_at'] : date('Y-m-d H:i:s');
        if (isset($data['quote_id']) AND (int)$data['quote_id']>0) {
            $data['quote_ids']=self::generateQuoteIds($data['quote_id']);
        }
        $comment = new Comment();
        $comment->fromArray($data);
        $comment->save();
        $cid = $comment->id;
        if ($cid) {
            //更新话题评论数
            if (isset($data['bbs_unit_id']) AND $data['bbs_unit_id']) {
                Model_Bbs::updateCommentNum($data['bbs_unit_id'], $data['user_id'],$cid);
            }
            //更新活动评论数
            if (isset($data['event_id']) AND $data['event_id']) {
                Model_Event::updateCommentNum($data['event_id']);
            }
            //更新新鲜事评论数
            if (isset($data['weibo_id']) AND $data['weibo_id']) {
                Model_Weibo::updateCommentNum($data['weibo_id']);
            }
            if (isset($data['news_id']) AND $data['news_id']) {
                Model_News::updateCommentNum($data['news_id']);
            }
            if (isset($data['pic_id']) AND $data['pic_id']) {
                Model_Album::updateCommentNum($data['pic_id']);
            }
            if (isset($data['class_unit_id']) AND $data['class_unit_id']) {
                Model_Classroom::updateBbsUnitCommentNum($data['class_unit_id']);
            }
            return $cid;
        } else {
            return false;
        }
    }

    //我最近评论过的新鲜事ids
    public static function cmtWeibo($user_id) {
        $weibo = Doctrine_Query::create()
                ->select('weibo_id')
                ->from('Comment')
                ->where('user_id = ?', $user_id)
                ->andWhere('weibo_id IS NOT NULL')
                ->limit(50)
                ->orderBy('post_at DESC')
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        $ids = array();
        if (count($weibo) == 0) {
            $ids = array(0);
        } else {
            $ids = $weibo;
        }
        return $ids;
    }

    //生成引用评论序列
    public static function generateQuoteIds($cid) {
        $quote_ids = null;
        if ($cid > 0) {
            $comment = Doctrine_Query::create()
                    ->from('Comment')
                    ->select('quote_ids')
                    ->where('id=?', $cid)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            if ($comment AND $comment['quote_ids']) {
                $quote_ids = $comment['quote_ids'] . ',' . $cid;
            } else {
                $quote_ids = $cid;
            }
        }
        return $quote_ids;
    }

    //获取被引用评论数组(内容为纯文本)
    public static function  getQuotes($str_ids) {
        $str_ids = str_replace(',,', ',', $str_ids);
        $cmt_ids = explode(',', $str_ids);
        $total_quote = count($cmt_ids);
        $quotes=array();
        if ($total_quote > 0) {
            $comments = Doctrine_Query::create()
                    ->select('c.content,c.post_at,c.user_id')
                    ->addSelect('u.id,u.realname,u.start_year,u.speciality,u.sex')
                    ->from('Comment c')
                    ->leftJoin('c.User u')
                    ->whereIn('c.id', $cmt_ids)
                    ->orderBy('c.id ASC')
                    ->fetchArray();
            if($comments){
                foreach($comments AS $key=>$c){
                    $quotes[$key]['uid']=$c['user_id'];
                    $quotes[$key]['realname']=$c['User']['realname'];
                    $quotes[$key]['speciality']=$c['User']['start_year'] && $c['User']['speciality'] ? $c['User']['start_year'] . '级' . $c['User']['speciality'] : $c['User']['speciality'];
                    $quotes[$key]['create_date']=$c['post_at'];
                    $quotes[$key]['str_create_date']=Date::ueTime($c['post_at']);
                    $c['content']=  str_replace('&nbsp;','',$c['content']);
                    $quotes[$key]['content']=Text::limit_chars(trim(strip_tags($c['content']),150));
                }
            }
        }
        return $quotes;
    }

}
