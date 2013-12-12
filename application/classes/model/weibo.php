<?php

/**
  +-----------------------------------------------------------------
 * 名称：新鲜事模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-17 下午5:10
  +-----------------------------------------------------------------
 */
class Model_Weibo {

    //分发微博
    public static function post($data) {

        //用户为正
        if (@$data['user_id'] > 0 AND @$data['content']) {

            //非法关键词检查
            $filter_str = Model_Filter::check($data);
            if ($filter_str) {
                $msg = '包含非法关键词“' . $filter_str . '”，请修改后重试，谢谢！';
                return array('error' => True, 'msg' => $msg);
            }

            //检查内容
            if (strlen($data['content']) < 1) {
                $msg = '很抱歉，内容不能为空 :)';
                return array('error' => True, 'msg' => $msg);
            }

            //检测字数
            if (strlen($data['content']) > 500) {
                $msg = '很抱歉，字数太多啦ss~！'.strlen($data['content']);
                return array('error' => True, 'msg' => $msg);
            }

            //获取所有#话题#
            preg_match_all('/\#([^\#|.]+)\#/', $data['content'], $topic_array);

            //将“@姓名”转换为“[u=id]姓名[/u]”
            if (strstr($data['content'], '@')) {
                //所有我关注的人
                $mark = Doctrine_Query::create()
                        ->select('m.user,u.realname AS realname')
                        ->from('UserMark m')
                        ->leftJoin('m.MUser u')
                        ->where('m.user_id = ?', $data['user_id'])
                        ->fetchArray();

                if (count($mark) > 0) {
                    //替换所有@名称
                    foreach ($mark AS $m) {
                        $patterns[] = '/@' . $m['realname'] . '/';
                        $replacements[] = '[u=' . $m['user'] . ']' . $m['realname'] . '[/u]';
                    }
                    $data['content'] = preg_replace($patterns, $replacements, $data['content']);
                }
            }

            //检测#话题#名称
            if (strstr($data['content'], '#请修改话题名称#')) {
                $msg = '很抱歉，话题名称不能为空！';
                return array('error' => True, 'msg' => $msg);
            }

            //当前输入的内容
            $content = $data['content'];

            //如果是转发，追加之前转发内容
            if (@$data['forward_content']) {
                $data['content'] = $data['content'] . '//' . $data['forward_content'];
            }

            //插入图片
            if (@$data['img_paths']) {
                $img_paths_array = explode('||', trim($data['img_paths']));
                $data['img_path'] = $img_paths_array[0];
            }

            //作者加入的校友会
            $aaIds = Model_User::aaIds($data['user_id']);
            if (count($aaIds) == 0) {
                $msg = '很抱歉，您还没有加入任何校友会，暂时不能发布！';
                return array('error' => True, 'msg' => $msg);
            }

            //关于本会的新鲜事(非个人)
            if (isset($data['about_org']) AND $data['about_org']) {
                $data['about_org'] = True;
            }

            //有来源校友会
            if (isset($data['aa_id']) AND $data['aa_id']) {
                $data['aa_id'] = $data['aa_id'];
            }
            
            //内容保存到数据库
            $data['post_at'] = isset($data['post_at']) ? $data['post_at'] : date('Y-m-d H:i:s');
            $weibo = new WeiboContent();
            $weibo->fromArray($data);
            $weibo->save();
            $wid = $weibo->id;

            //更新话题总数
            if (isset($topic_array[1][0])) {
                self::updateTopic($topic_array[1], $data['user_id']);
            }

            //如果转发并评论，将内容写入各自评论
            if ($wid AND count(@$data['comment_weibos']) > 0) {
                self::postCommentToWeibo($data['comment_weibos'], $data['user_id'], $content);
            }

            //如果是转发更新被转发的次数
            if ($wid AND @$data['from_forward']) {
                self::updataRetweetNum($data['from_forward']);
            }

            //返回微博id
            return $wid;
        }
        //作者为空
        elseif (@empty($data['user_id'])) {
            return array('error' => True, 'msg' => '作者不能为空');
        }
        //内容为空
        elseif (@empty($data['content'])) {
            return array('error' => True, 'msg' => '内容不能为空');
        } else {

        }
    }

    //将微博归属到某校友会(暂停使用)
    public static function addWeiboToAa($wid, $aa_id, $user_id, $topic_array, $is_about_aa) {

        if ($wid > 0 AND $aa_id > 0) {
            $weiboAa = new AaWeibo();
            $weiboAa->aa_id = $aa_id;
            $weiboAa->content_id = $wid;
            $weiboAa->about_org = $is_about_aa;

            //话题默认为第一个##内容
            if (isset($topic_array[1][0])) {
                $weiboAa->topic = $topic_array[1][0];
            }
            $weiboAa->save();

            //更新插入的所有话题数目
            if (isset($topic_array[1][0])) {
                self::updateTopic($topic_array[1], $aa_id, $user_id);
            }

            return $weiboAa->id;
        } else {
            return False;
        }
    }

    //对原帖进行评论(转发时)
    public static function postCommentToWeibo($wids, $user_id, $content) {
        if (count($wids) > 0) {
            foreach ($wids as $wid) {
                $comment = new Comment();
                $cpost['weibo_id'] = $wid;
                $cpost['post_at'] = date('Y-m-d H:i:s');
                $cpost['user_id'] = $user_id;
                $comment['content'] = $content;
                $comment->fromArray($cpost);
                $comment->save();

                //更新评论数量
                self::updateCommentNum($wid);
            }
        }
    }

    //更新被转发的次数
    public static function updataRetweetNum($wid) {
        if ($wid) {
            $retweet_num = Doctrine_Query::create()
                    ->select('id')
                    ->from('WeiboContent')
                    ->where('from_forward = ?', $wid)
                    ->count();
            Doctrine_Query::create()
                    ->update('WeiboContent')
                    ->where('id= ?', $wid)
                    ->set('forward_num', $retweet_num)
                    ->execute();
        }
    }

    //更新话题
    public static function updateTopic($topic_array, $user_id) {

        if (!$topic_array OR !is_array($topic_array) OR count($topic_array) == 0) {
            return FALSE;
        }

        foreach ($topic_array as $t) {

            //查找是否已有话题条话
            $topic = Doctrine_Query::create()
                    ->from('WeiboTopics')
                    ->where('topic ="' . $t . '"')
                    ->limit(1)
                    ->fetchOne();

            //统计话题在各个校友会中的数量
            if ($topic) {
                $count = Doctrine_Query::create()
                        ->select('id')
                        ->from('WeiboContent')
                        ->where('content LIKE "%#' . $t . '#%"')
                        ->count();
                $topic->num = $count;
                $topic->save();
            }

            //添加新话题
            else {
                $topic = new WeiboTopics();
                $topic->topic = $t;
                $topic->num = 1;
                $topic->user_id = $user_id;
                $topic->save();
            }
        }
    }

    //更新评论总数
    public static function updateCommentNum($weibo_id) {
        $total_comments = Doctrine_Query::create()
                ->from('Comment')
                ->where('weibo_id = ?', $weibo_id)
                ->count();

        Doctrine_Query::create()
                ->update('WeiboContent')
                ->where('id= ?', $weibo_id)
                ->set('reply_num', $total_comments)
                ->execute();

        return $total_comments;
    }

}

?>