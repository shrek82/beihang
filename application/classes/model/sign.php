<?php

/**
  +-----------------------------------------------------------------
 * 名称：活动报名模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-11-9 下午2:48
  +-----------------------------------------------------------------
 */
class Model_Sign {

    private $event_id;
    private $event;
    private $_uid;
    private $user;
    private $error;
    private $action;
    private $userSign;
    public $postData;

    //初始化报名模型(活动id，用户id,$action)
    //action:addForm,add,updateForm,update
    function __construct($event_id = null, $user_id = null, $action = 'addForm') {
        $this->error = false;
        $this->_uid = $user_id;
        $this->event_id = $event_id;
        $this->action = $action;
        $this->event = Db_Event::getEventById($event_id);
        $this->user = Db_User::getInfoById($this->_uid);
    }

    //能否执行addFom和add和updateForm和update
    public function signValidation() {

        //无指定用户id
        if (!$this->_uid) {
            $this->error = '很抱歉，您还没有登录，请先登录！';
        }

        //每次都需要验证的
        if (!$this->event_id) {
            $this->error = '很抱歉，请指定活动id！';
            return false;
        }

        //无活动信息
        if (!$this->event) {
            $this->error = '很抱歉，活动不存在或已被删除！';
            return false;
        }


        //无用户信息
        if (!$this->user) {
            $this->error = '很抱歉，用户不存在；';
            return false;
        }

        //活动基本限制
        if (time() >= strtotime($this->event['start'])) {
            $this->error = '很抱歉，活动已经结束，欢迎下次参加！';
            return false;
        }

        //活动基本限制
        if ($this->event['is_stop_sign']) {
            $this->error = '很抱歉，暂时停止活动报名，如有问题，请与管理员联系！';
            return false;
        }

        //用户没有通过审核
        if ($this->user['role'] != '校友(已认证)' AND $this->user['role'] != '管理员') {
            $this->error = '很抱歉，您还没有通过审核，暂时不能报名；';
            return false;
        }

        //用户资料完整性验证
        if (!$this->userInfoValidation()) {
            return false;
        }

        //活动积分限制
        if ($this->event['points_at_least'] > 0 && $this->user['point'] < $this->event['points_at_least']) {
            $this->error = '很抱歉，本次活动报名至少需要' . $this->event['points_at_least'] . '点积分，您目前的积分为' . $this->user['point'] . '分 :(';
            return false;
        }

        //情况1、新报名表单
        if ($this->action == 'addForm') {

            //是否是重复报名
            if (self::isJoined($this->event_id, $this->_uid)) {
                $this->error = '很抱歉，您已经报名了本次活动，不需要再次报名了；';
                return false;
            }
        }

        //情况2、新添加报名至数据库
        if ($this->action == 'add' OR $this->action == 'update') {

            //有活动分组必须选择分组
            if (isset($this->postData['category_id']) AND empty($this->postData['category_id'])) {
                $this->error = '很抱歉，您还没有选择报名分组！';
                return false;
            }

            //活动备注内容字数限制
            if (UTF8::strlen($this->postData['remarks']) > 255) {
                $this->error = '很抱歉，补充文字字数超过限制(255)，请编辑后重试:(';
                return false;
            }
        }

        //报名人数及票数数量验证
        $sign_all = Doctrine_Query::create()
                ->select('SUM(s.num) as sign_num,SUM(s.tickets) as total_tickets,count(s.id) as total_user')
                ->from('EventSign s')
                ->where('s.event_id = ?', $this->event_id);


        //报名表单
        if ($this->action == 'addForm') {
            //查询所有报名记录
            $sign_all = $sign_all->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //检查名额是否充足
            if ($this->event['sign_limit'] > 0 AND $this->event['sign_limit'] <= $sign_all['sign_num']) {
                $this->error = '很抱歉，暂时没有名额了！';
                return false;
            }
            //检查门票是否充足
            if ($this->event['need_tickets'] AND $this->event['total_tickets'] <= $sign_all['total_tickets']) {
                $this->error = '很抱歉，暂时没有门票了！';
                return false;
            }
        }
        //添加新报名状态
        elseif ($this->action == 'add') {
            //查询所有报名记录
            $sign_all = $sign_all->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //检查名额是否充足
            if ($this->event['sign_limit'] > 0 AND $this->event['sign_limit'] <= $sign_all['sign_num'] + $this->postData['num']) {
                $this->error = '很抱歉，名额不足，请重新选择名额！';
                return false;
            }
            //检查门票是否充足
            if ($this->event['need_tickets'] AND $this->event['total_tickets'] <= $sign_all['total_tickets'] + $this->postData['tickets']) {
                $this->error = '很抱歉，门票不足，请重新选择门票数量！';
                return false;
            }
        }
        //保存活动报名信息
        elseif ($this->action == 'update') {
            $sign_all = $sign_all->addWhere('s.user_id<>?', $this->_uid)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            //检查修改后名额是否充足
            if ($this->event['sign_limit'] > 0 AND $this->event['sign_limit'] <= $sign_all['sign_num'] + $this->postData['num']) {
                $this->error = '很抱歉，名额不足，请重新选择名额！';
                return false;
            }
            //检查修改后门票是否充足
            if ($this->event['need_tickets'] AND $this->event['total_tickets'] <= $sign_all['total_tickets'] + $this->postData['tickets']) {
                $this->error = '很抱歉，门票不足，请重新选择门票数量！';
                return false;
            }
        }
        //保存活动报名信息
        elseif($this->action=='updateForm') {
        }
        //保存活动报名信息
        else {
            $this->error = '很抱歉，操作失败！';
            return false;
        }

        return true;
    }

    //用户基本资料是否完整
    //返回true或false
    public function userInfoValidation() {

        //联系信息
        $userContact = Doctrine_Query::create()
                ->from('UserContact')
                ->where('user_id=?', $this->_uid)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //工作信息
        $userWork = Doctrine_Query::create()
                ->from('UserWork')
                ->where('user_id=?', $this->_uid)
                ->orderBy('id DESC')
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //联系方式
        if (!$userContact) {
            $this->error = '很抱歉，您还没有添加任何联系信息；';
            return false;
        }

        //电话是否填写
        elseif (empty($userContact['mobile'])) {
            $this->error = '很抱歉，您的手机尚未完善，暂时不能报名哦:(' . '&nbsp;&nbsp;<a href="/user_info/base/" target="_blank">现在就去完善</a>';
            return false;
        }
        //工作
        elseif (!$userWork) {
            $this->error = '很抱歉，您尚未添加工作信息，请先添加！' . '&nbsp;&nbsp;<a href="/user_info/work/" target="_blank">现在就去完善</a>';
            return false;
        }

        //公司及职务
        elseif (empty($userWork['company']) OR empty($userWork['job'])) {
            $this->error = '很抱歉，您的工作单位或职务为空，请完善后报名，谢谢！' . '&nbsp;&nbsp;<a href="/user_info/work/" target="_blank">现在就去完善</a>';
            return false;
        } else {
            return true;
        }
    }

    //当前用户是否已经报名
    public static function isJoined($event_id, $user_id = 0) {
        $r = Doctrine_Query::create()
                ->select('id')
                ->from('EventSign')
                ->where('event_id = ? AND user_id = ?', array($event_id, $user_id))
                ->execute(array(), 6);
        return (bool) $r;
    }

    //返回错误
    public function getError() {
        return $this->error;
    }

    public function getEvent() {
        return $this->event;
    }

    //返回用户报名信息
    public function getUserSign() {
        return $this->userSign;
    }

    //保存或添加报名信息
    public function signSub() {

        if (!$this->signValidation()) {
            return False;
        }

        if(trim($this->postData['remarks'])=='没啥说的，一定准时到场～'){
             $this->postData['remarks']=null;
        }

        if(isset($this->postData['is_anonymous']) AND $this->postData['is_anonymous']){
             $this->postData['is_anonymous']=1;
        }
        else{
            $this->postData['is_anonymous']=0;
        }

        //添加新报名
        if ($this->action == 'add') {
            $sign = new EventSign();
            $sign->event_id = $this->event_id;
            $sign->num = Arr::get($this->postData, 'num');
            $sign->tickets = Arr::get($this->postData, 'tickets');
            $sign->category_id = Arr::get($this->postData, 'category_id');
            $sign->receive_address = Arr::get($this->postData, 'receive_address');
            $sign->is_anonymous = Arr::get($this->postData, 'is_anonymous');
            $sign->remarks = Arr::get($this->postData, 'remarks');
            $sign->user_id = $this->_uid;
            $sign->sign_at = date('Y-m-d H:i:s');
            $sign->save();
            if ($sign->id) {
                return $sign->id;
            } else {
                $this->error = '很抱歉，报名失败，可能是网站错误，请与管理员联系';
                return False;
            }
        }
        //保存修改
        elseif ($this->action == 'update') {
            $this->userSign = Model_Event::getUserSign($this->event_id, $this->_uid);
            if ($this->userSign) {
                $this->userSign->synchronizeWithArray($this->postData);
                $this->userSign->save();
                return $this->userSign->id;
            } else {
                $this->error = '很抱歉，您还没有报名！';
                return False;
            }
        }
        return false;
    }

}