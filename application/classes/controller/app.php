<?php

//测试模块
class Controller_App extends Layout_Main {

    public $_config;
    public $_keeptime;
    public $_user_id;

    public function before() {
        parent::before();
        header("Content-Type:text/html; charset=utf-8");
        $this->_config = Kohana::config('app');
        $this->_keeptime = time() + Date::MONTH;
        $this->_user_id = $this->_sess->get('id');
    }

    //新浪微博
    function action_binding($server = 'sina') {
        if (!$this->_user_id) {
            echo '很抱歉，您还没有登录校友网，请先<a href="/user/login" target="_blank">登录</a>，然后刷新本页重试绑定，谢谢！';
            exit;
        }
        if ($server == 'sina') {
            //我的绑定
            $binding = Model_Binding::getBinding(array(
                        'user_id' => $this->_user_id,
                        'service' => 'sina',
            ));
            $view['binding'] = $binding;
            $view['bindingUrl'] = '';
            if (!$binding) {
                Candy::import('sinaWeiboApi');
                $oauth = new SaeTOAuthV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY']);
                $view['bindingUrl'] = $oauth->getAuthorizeURL($this->_config->sina['WB_CALLBACK_URL']);
            }
            $this->_render('_body', $view, 'app/sina');
        }
    }

    //绑定回执处理
    function action_callback($service = null) {
        if (!$this->_user_id) {
            echo '很抱歉，您还没有登录校友网，请先<a href="/user/login" target="_blank">登录</a>，然后刷新本页重试绑定，谢谢！';
            exit;
        }
        switch ($service) {
            case 'sina':
                $this->sinaCallback();
                break;
            case 'renren':
                $this->_redirect(('user/account'));
                break;
        }
    }

    //执行新浪授权后页面
    function sinacallback() {
        //ErrorException [ Deprecated ]: Call-time pass-by-reference has been deprecated

        Candy::import('sinaWeiboApi');
        $oauth = new SaeTOAuthV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY']);
        //初步得到认证
        $code = Arr::get($_GET, 'code');
        if ($code) {
            $keys = array();
            $keys['code'] = $code;
            $keys['redirect_uri'] = $this->_config->sina['WB_CALLBACK_URL'];
            $token = null;
            //获取正式授权:AccessToken
            try {
                $token = $oauth->getAccessToken('code', $keys);
            } catch (OAuthException $e) {
                
            }

            $view = array();

            //获取了授权认证
            //$token是一个包含了access_token和uid的数组
            if ($token) {

                //已经登录校友网
                if ($this->_user_id) {

                    //针对特殊校友会的微博帐号绑定
                    //校友总会的微博帐号
                    if ($token['uid'] == '2173025362') {
                        $aa_id = 0;
                        $search = array(
                            'fields' => 'id',
                            'aa_id' => $aa_id,
                            'service' => 'sina',
                        );
                    } else {
                        $user_id = $this->_user_id;
                        $search = array(
                            'fields' => 'id',
                            'user_id' => $this->_user_id,
                            'service' => 'sina',
                        );
                    }

                    //查询是否已经绑定(aa_id优先)
                    $binding = Model_Binding::getBinding($search);

                    //保存至数据库的信息
                    $data = array(
                        'aa_id' => isset($aa_id) ? $aa_id : null,
                        'user_id' => isset($aa_id) ? null : $user_id,
                        'service' => 'sina',
                        'code' => $code,
                        'access_token' => $token['access_token'],
                        'expires_in' => date('Y-m-d H:i:s', ((int) strtotime(date('Y-m-d H:i:s')) + (int) $token['expires_in'])),
                        'uid' => $token['uid'],
                        'get_access_token_code' => $code
                    );

                    //还没有绑定，新建绑定记录
                    if (!$binding) {
                        $binding_id = Model_Binding::create($data);
                    }
                    //已经绑定过，再次更新
                    else {
                        $binding_id = Model_Binding::update($binding['id'], $data);
                    }
                }

                //同步我的微博帐号信息
                $synchronous_info = $this->synchronous($this->_user_id);
                $view['synchronous_info'] = $synchronous_info;

                //绑定微博增加积分
                Db_User::updatePoint('weibo_binding');
            } else {
                $view['error'] = '很遗憾，微博绑定失败了。可能是请求超时，请与管理员联系或重试！';
            }
        } else {
            $view['error'] = '很遗憾，绑定失败！原因：未收到任何请求返回信息。';
        }

        $this->_render('_body', $view);
    }

    //手动同步
    function action_synchronous($user_id = null) {
        $this->auto_render = false;
        $user_id = isset($user_id) ? $user_id : $this->_user_id;
        Candy::import('sinaWeiboApi');
        $binding_info = $this->synchronous($user_id);
        if ($binding_info) {
            echo Kohana::debug($binding_info);
        } else {
            echo '很遗憾，同步失败！';
        }
    }

    //同步个人信息
    function synchronous($user_id) {

        //我的绑定
        $binding = Model_Binding::getBinding(array(
                    'user_id' => $user_id,
                    'service' => 'sina',
        ));

        if (!$binding) {
            return false;
        }

        $c = new SaeTClientV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY'], $binding['access_token']);

        //根据ID获取用户等基本信息
        $user_info = $c->show_user_by_id($binding['uid']);
        if ($user_info) {
            unset($user_info['id']);
            if (isset($user_info['status'])) {
                unset($user_info['status']);
            }
            Model_Binding::update($binding['id'], $user_info);
            return $user_info;
        } else {
            return false;
        }
    }

    //微博数据
    function action_weibolistdata() {
        $this->auto_render = false;
        $binding = Model_Binding::getBinding(array(
                    'user_id' => $this->_user_id,
                    'service' => 'sina',
        ));

        if (!$binding) {
            $this->_redirect('app/sina');
            exit;
        }

        Candy::import('sinaWeiboApi');

        $c = new SaeTClientV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY'], $binding['access_token']);
        $weibolist = $c->user_timeline_by_id($binding['uid'], 1, 10, 0, 0, 0, 1);
        if (isset($weibolist['statuses'])) {
            $list = $weibolist['statuses'];
            $view['list'] = $list;
        } else {
            if (isset($weibolist['error_code'])) {
                $view['error'] = "获取失败，错误：{$weibolist['error_code']}:{$weibolist['error']}";
            } else {
                $view['error'] = "获取失败";
            }
        }

        echo View::factory('app/weibolistdata', $view);
    }

    //采集当前微博的原贴
    function action_autoCollect() {
        $this->auto_render = false;
        Candy::import('sinaWeiboApi');

        //要获取校友会id,0为总会
        $aa_id = 0;

        //page 页码
        $page = Arr::get($_GET, 'page', 1);
        //每次返回的最大记录数，最多返回200条，默认50。
        $count = Arr::get($_GET, 'count', 10);
        //若指定此参数，则返回ID比since_id大的微博（即比since_id时间晚的微博），默认为0。
        $since_id = 0;
        //若指定此参数，则返回ID小于或等于max_id的微博，默认为0。
        $max_id = 0;
        //过滤类型ID，0：全部、1：原创、2：图片、3：视频、4：音乐，默认为0。
        $feature = 0;
        //返回值中user信息开关，0：返回完整的user信息、1：user字段仅返回uid，默认为0。
        $trim_user = 1;
        //获取指定校友会微博授权信息
        $binding = Model_Binding::getBinding(array(
                    'service' => 'sina',
                    'user_id' => 28225,
                    'fields' => 'id,uid,user_id,aa_id,access_token',
        ));
        //没有绑定信息
        if (!$binding) {
            echo 'sorry,you are Not authorized!';
            exit;
        }
        //最后一次吃采集信息
        else {
            $last_colletion = Doctrine_Query::create()
                    ->from('SinaWeibo')
                    ->select('id,idstr,wid,mid,aa_id')
                    ->where('aa_id=?', $aa_id)
                    ->orderBy('id DESC')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //只采集上次以后的
            if ($last_colletion) {
                $since_id = $last_colletion['idstr'];
            }
        }

        $c = new SaeTClientV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY'], $binding['access_token']);
        //新浪微博用户id
        $sina_uid = $binding['uid'];
        //采集
        $weibolist = $c->user_timeline_by_id($sina_uid, $page, $count, $since_id, $max_id, $feature, $trim_user);

        if (isset($weibolist['error'])) {
            echo '很抱歉，微博获取失败！';
            echo Kohana::debug($weibolist);
            exit;
        }

        //消息列表
        $list = isset($weibolist['statuses']) ? $weibolist['statuses'] : array();

        if (count($list) > 0) {

            $sinaweibo = new Doctrine_Collection('SinaWeibo');
            //循环添加
            for ($i = count($list) - 1; $i >= 0; $i--) {
                $sinaweibo[$i]->uid = $sina_uid;
                $sinaweibo[$i]->user_id = null;
                $sinaweibo[$i]->aa_id = $aa_id;
                $sinaweibo[$i]->idstr = $list[$i]['idstr'];
                $sinaweibo[$i]->wid = $list[$i]['id'];
                $sinaweibo[$i]->mid = $list[$i]['mid'];
                $sinaweibo[$i]->text = $list[$i]['text'];
                $sinaweibo[$i]->created_at = date("Y-m-d H:i:s", strtotime($list[$i]['created_at']));
                $sinaweibo[$i]->source = $list[$i]['source'];
                $sinaweibo[$i]->in_reply_to_status_id = $list[$i]['in_reply_to_status_id'];
                $sinaweibo[$i]->in_reply_to_user_id = $list[$i]['in_reply_to_user_id'];
                $sinaweibo[$i]->in_reply_to_screen_name = $list[$i]['in_reply_to_screen_name'];
                $sinaweibo[$i]->bmiddle_pic = isset($list[$i]['bmiddle_pic']) ? $list[$i]['bmiddle_pic'] : null;
                $sinaweibo[$i]->original_pic = isset($list[$i]['original_pic']) ? $list[$i]['original_pic'] : null;
                $sinaweibo[$i]->thumbnail_pic = isset($list[$i]['thumbnail_pic']) ? $list[$i]['thumbnail_pic'] : null;
                $sinaweibo[$i]->reposts_count = $list[$i]['reposts_count'];
                $sinaweibo[$i]->comments_count = $list[$i]['comments_count'];
                $sinaweibo[$i]->favorited = $list[$i]['favorited'];
                $sinaweibo[$i]->truncated = $list[$i]['truncated'];
                $sinaweibo[$i]->colletion_at = date("Y-m-d H:i:s");
            }
            $sinaweibo->save();
            echo 'save ' . count($list);
        } else {
            echo 'not update! ';
        }
    }

    //采集当前微博的原贴
    function action_autoCollectHangzhou() {
        $this->auto_render = false;
        Candy::import('sinaWeiboApi');

        //要获取校友会id,0为总会
        $aa_id = 1;

        //page 页码
        $page = Arr::get($_GET, 'page', 1);
        //每次返回的最大记录数，最多返回100条，默认20。
        $count = Arr::get($_GET, 'count', 100);
        //若指定此参数，则返回ID比since_id大的微博（即比since_id时间晚的微博），默认为0。
        $since_id = 0;
        //若指定此参数，则返回ID小于或等于max_id的微博，默认为0。
        $max_id = 0;
        //过滤类型ID，0：全部、1：原创、2：图片、3：视频、4：音乐，默认为0。
        $feature = 0;
        //返回值中user信息开关，0：返回完整的user信息、1：user字段仅返回uid，默认为0。
        $trim_user = 1;
        //获取指定校友会微博授权信息
        $binding = Model_Binding::getBinding(array(
                    'service' => 'sina',
                    'user_id' => 18119,
                    'fields' => 'id,uid,user_id,aa_id,access_token,expires_in,code'
        ));
        //没有绑定信息
        if (!$binding) {
            echo 'sorry,you are Not authorized!';
            exit;
        }
        //最后一次吃采集信息
        else {

            //判断授权是否过期，过期自动申请授权
            if (strtotime(date('Y-m-d H:i:s')) > strtotime($binding["expires_in"])) {
                echo 'accent_token expires';
                exit;
            }


            $last_colletion = Doctrine_Query::create()
                    ->from('SinaWeibo')
                    ->select('*')
                    ->where('aa_id=?', $aa_id)
                    ->orderBy('mid DESC')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //只采集上次以后的
            if ($last_colletion) {
                $since_id = $last_colletion['idstr'];
                //$max_id = $last_colletion['idstr'];
            }
        }

        $c = new SaeTClientV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY'], $binding['access_token']);
        //新浪微博用户id
        $sina_uid = $binding['uid'];
        //采集
        $weibolist = $c->user_timeline_by_id($sina_uid, $page, $count, $since_id, $max_id, $feature, $trim_user);

        if (isset($weibolist['error'])) {
            echo '很抱歉，微博获取失败！';
            echo Kohana::debug($weibolist);
            exit;
        }

        //消息列表
        $list = isset($weibolist['statuses']) ? $weibolist['statuses'] : array();

        if (count($list) > 0) {

            $sinaweibo = new Doctrine_Collection('SinaWeibo');
            //循环添加
            for ($i = count($list) - 1; $i >= 0; $i--) {
                $sinaweibo[$i]->uid = $sina_uid;
                $sinaweibo[$i]->user_id = null;
                $sinaweibo[$i]->aa_id = $aa_id;
                $sinaweibo[$i]->idstr = $list[$i]['idstr'];
                $sinaweibo[$i]->wid = $list[$i]['id'];
                $sinaweibo[$i]->mid = $list[$i]['mid'];
                $sinaweibo[$i]->text = $list[$i]['text'];
                $sinaweibo[$i]->created_at = date("Y-m-d H:i:s", strtotime($list[$i]['created_at']));
                $sinaweibo[$i]->source = $list[$i]['source'];
                $sinaweibo[$i]->in_reply_to_status_id = $list[$i]['in_reply_to_status_id'];
                $sinaweibo[$i]->in_reply_to_user_id = $list[$i]['in_reply_to_user_id'];
                $sinaweibo[$i]->in_reply_to_screen_name = $list[$i]['in_reply_to_screen_name'];
                $sinaweibo[$i]->bmiddle_pic = isset($list[$i]['bmiddle_pic']) ? $list[$i]['bmiddle_pic'] : null;
                $sinaweibo[$i]->original_pic = isset($list[$i]['original_pic']) ? $list[$i]['original_pic'] : null;
                $sinaweibo[$i]->thumbnail_pic = isset($list[$i]['thumbnail_pic']) ? $list[$i]['thumbnail_pic'] : null;
                $sinaweibo[$i]->reposts_count = $list[$i]['reposts_count'];
                $sinaweibo[$i]->comments_count = $list[$i]['comments_count'];
                $sinaweibo[$i]->favorited = $list[$i]['favorited'];
                $sinaweibo[$i]->truncated = $list[$i]['truncated'];
                $sinaweibo[$i]->colletion_at = date("Y-m-d H:i:s");
            }
            $sinaweibo->save();
            echo 'save ' . count($list);
        } else {
            echo 'not update! ';
        }
    }

    //获取对当前微博的评论
    function action_getComments() {
        $this->auto_render = false;
        Candy::import('sinaWeiboApi');
        //获取指定校友会微博授权信息
        $binding = Model_Binding::getBinding(array(
                    'service' => 'sina',
                    'user_id' => 28225,
                    'fields' => 'id,uid,user_id,aa_id,access_token',
        ));

        //没有绑定信息
        if (!$binding) {
            echo 'sorry,you are Not authorized!';
            exit;
        }
        //最后一次吃采集信息
        else {
            $since_id = 0;
            $last_colletion = Doctrine_Query::create()
                    ->from('SinaComments')
                    ->select('cmt_id,cmt_idstr')
                    ->orderBy('id DESC')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //只采集上次以后的
            if ($last_colletion) {
                $since_id = $last_colletion['cmt_idstr'];
            }
        }
        $c = new SaeTClientV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY'], $binding['access_token']);
        $sina_uid = $binding['uid'];
        //获取当前登录用户所接收到的评论列表
        $comments = $c->comments_to_me(1, 50, $since_id);
        if (isset($comments['error'])) {
            echo '很抱歉，评论获取失败！';
            echo Kohana::debug($comments);
            exit;
        }

        $list = isset($comments['comments']) ? $comments['comments'] : array();

        if (count($list) > 0) {
            $sinaComments = new Doctrine_Collection('SinaComments');
            //循环添加
            for ($i = count($list) - 1; $i >= 0; $i--) {
                $sinaComments[$i]->cmt_id = isset($list[$i]['id']) ? $list[$i]['id'] : null;
                $sinaComments[$i]->cmt_idstr = isset($list[$i]['idstr']) ? $list[$i]['idstr'] : null;
                $sinaComments[$i]->text = isset($list[$i]['text']) ? $list[$i]['text'] : null;
                $sinaComments[$i]->source = isset($list[$i]['source']) ? $list[$i]['source'] : null;
                $sinaComments[$i]->cmt_uid = isset($list[$i]['user']['idstr']) ? $list[$i]['user']['idstr'] : null;
                $sinaComments[$i]->cmt_mid = isset($list[$i]['mid']) ? $list[$i]['mid'] : null;
                $sinaComments[$i]->cmt_name = isset($list[$i]['user']['name']) ? $list[$i]['user']['name'] : null;
                $sinaComments[$i]->cmt_screen_name = isset($list[$i]['user']['screen_name']) ? $list[$i]['user']['screen_name'] : null;
                $sinaComments[$i]->profile_image_url = isset($list[$i]['user']['profile_image_url']) ? $list[$i]['user']['profile_image_url'] : null;
                $sinaComments[$i]->created_at = date("Y-m-d H:i:s", strtotime($list[$i]['created_at']));
                $sinaComments[$i]->avatar_large = isset($list[$i]['user']['avatar_large']) ? $list[$i]['user']['avatar_large'] : null;
                $sinaComments[$i]->weibo_id = isset($list[$i]['status']['idstr']) ? $list[$i]['status']['idstr'] : null;
                $sinaComments[$i]->colletion_at = date("Y-m-d H:i:s");
            }
            $sinaComments->save();
            echo 'save ' . count($list);
        } else {
            echo 'not update! ';
        }
    }

    //删除微博内容
    function action_delete() {
        $cid = Arr::get($_GET, 'cid');
        $binding = Model_Binding::getBinding(array(
                    'user_id' => $this->_user_id,
                    'service' => 'sina',
        ));

        Candy::import('sinaWeiboApi');
        $c = new SaeTClientV2($this->_config->sina['WB_AKEY'], $this->_config->sina['WB_SKEY'], $binding['access_token']);
        $ret = $c->delete($cid);
        if (isset($ret['error_code']) && $ret['error_code'] > 0) {
            echo "发送失败，错误：{$ret['error_code']}:{$ret['error']}</p><br>";
            exit;
        }
    }

}

?>
