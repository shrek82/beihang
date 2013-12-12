<?php

# 表单数据校验规则
return array(
    'rules' => array(
        'auth' => array(
            'realname' => array('not_empty' => null), //'chinese' => null
            'start_year' => array('numeric' => null),
            'graduation_year' => array('numeric' => null),
            'speciality' => array('not_empty' => null),
        ),
        'register' => array(
            'realname' => array('not_empty' => null, 'max_length' => array(30)), //'chinese' => null,
            'sex' => array('not_empty' => null),
            'account' => array('not_empty' => null, 'email' => null),
            'password' => array('not_empty' => null, 'min_length' => array(6)),
            'agreement' => array('not_empty' => null),
            //'city' => array('not_empty' => null),
            'mobile' => array('not_empty' => null),
        ),
        'login' => array(
            'account' => array('not_empty' => null, 'email' => null),
            'password' => array('not_empty' => null, 'min_length' => array(3)),
        ),
        'news_category' => array(
            'name' => array('not_empty' => null),
            'order_num' => array('numeric' => null),
        ),
        'news_special' => array(
            'name' => array('not_empty' => null),
        ),
        'news' => array(
            'title' => array('not_empty' => null),
            'content' => array('not_empty' => null),
        ),
        'comment' => array(
            'content' => array('not_empty' => null, 'min_length' => array(2), 'max_length' => array(8000)),
        ),
        'event' => array(
            'aa_id' => array('not_empty' => null),
            'etype' => array('not_empty' => null),
            'title' => array('not_empty' => null),
            'start' => array('not_empty' => null, 'date' => null),
            'finish' => array('not_empty' => null, 'date' => null),
            'sign_limit' => array('numeric' => null),
            'address' => array('not_empty' => null)
        ),
        'event_static' => array(
            'title' => array('not_empty' => null),
        ),
        'bbs_post' => array(
            'bbs_id' => array('not_empty' => null, 'numeric' => null),
            'title' => array('not_empty' => null),
        //'content' => array('not_empty' => null)
        ),
        'bbs_focus' => array(
            'bbs_unit_id' => array('not_empty' => null, 'numeric' => null),
        ),
        'category' => array(
            'name' => array('not_empty' => null),
            'order_num' => array('numeric' => null),
        ),
        'aa_contact' => array(
            'zip' => array('numeric' => null),
            'website' => array('url' => null),
        ),
        'aa_info' => array(
            'title' => array('not_empty' => null),
        ),
        'aa_show' => array(
            'title' => array('not_empty' => null),
            'url' => array('not_empty' => null, 'url' => null),
        ),
        'main_aa' => array(
            'name' => array('not_empty' => null),
            'zip' => array('numeric' => null),
            'mail' => array('email' => null),
        ),
        'club' => array(
            'name' => array('not_empty' => null),
            'title' => array('not_empty' => null),
            'user_id' => array('not_empty' => null),
        ),
        'msg' => array(
            'send_to' => array('not_empty' => null),
            'content' => array('not_empty' => null),
        ),
        'msg_reply' => array(
            'content' => array('not_empty' => null),
        ),
        'bubble' => array(
            'content' => array('not_empty' => null),
        ),
        'user_base' => array(
            'city' => array('not_empty' => null),
            'sex' => array('not_empty' => null),
            'mobile' => array('not_empty' => null),
            'tel' => array('phone' => array(array(11, 12, 13, 14, 15, 16, 17, 18, 19))),
            'qq' => array('numeric' => null),
        ),
        'reg_work' => array(
            'city' => array('not_empty' => null),
            'company' => array('not_empty' => null),
            'job' => array('not_empty' => null),
        // 'start_at' => array('not_empty' => null, 'date' => null),
        // 'leave_at' => array('date' => null),
        ),
        'user_work' => array(
            //'city'=>array('not_empty'=>null),
            'company' => array('not_empty' => null),
            'job' => array('not_empty' => null),
            'industry' => array('not_empty' => null),
        // 'start_at' => array('not_empty' => null, 'date' => null),
        // 'leave_at' => array('date' => null),
        ),
        'sys_msg' => array(
            'title' => array('not_empty' => null),
            'content' => array('not_empty' => null),
            'start_at' => array('not_empty' => null, 'date' => null),
            'expire_at' => array('not_empty' => null, 'date' => null),
        ),
        'people' => array(
            'name' => array('not_empty' => null),
            'abc' => array('not_empty' => null, 'alpha' => null, 'exact_length' => array(1)),
            'intro' => array('not_empty' => null),
        ),
        'newspic' => array(
            'title' => array('not_empty' => null),
            'url' => array('not_empty' => null),
        ),
        'links' => array(
            'name' => array('not_empty' => null),
            'url' => array('not_empty' => null),
        ),
        'content' => array(
            'type' => array('not_empty' => null),
            'title' => array('not_empty' => null),
        ),
        'content_category' => array(
            'name' => array('not_empty' => null)
        ),
        'abook' => array(
            'mobile' => array('not_empty' => null, 'phone' => array(array(11, 12))),
            'address' => array('not_empty' => null),
        ),
        'card' => array(
            'realname' => array('not_empty' => null, 'chinese' => null, 'max_length' => array(8)),
            'mobile' => array('not_empty' => null, 'phone' => array(11, 12)),
        ),
        'pubcontent' => array(
            'pub_id' => array('not_empty' => null),
            'col_id' => array('not_empty' => null),
            'title' => array('not_empty' => null),
        ),
        'publication' => array(
            'type' => array('not_empty' => null),
            'name' => array('not_empty' => null),
            'issue' => array('not_empty' => null),
        ),
        'elereport' => array(
            'title' => array('not_empty' => null),
            'issue' => array('not_empty' => null),
        ),
        'donate' => array(
            //年度捐赠表单
            'project' => array('not_empty' => null),
            'amount' => array('not_empty' => null),
            'donor' => array('not_empty' => null),
            'speciality' => array('not_empty' => null),
            'company' => array('not_empty' => null),
            'address' => array('not_empty' => null),
            'tel' => array('not_empty' => null),
            'email' => array('not_empty' => null),
            'donate_from' => array('not_empty' => null),
            'methods' => array('not_empty' => null),
        ),
        'donate_annual' => array(
            'project' => array('not_empty' => null),
            'amount' => array('not_empty' => null),
            'donor' => array('not_empty' => null),
            'donate_at' => array('not_empty' => null),
        ),
        'donate_fund' => array(
            'donor' => array('not_empty' => null),
            'donate_at' => array('not_empty' => null),
            'amount' => array('not_empty' => null),
        ),
        'classroom' => array(
            'intro' => array('not_empty' => null),
        ),
        'new_classroom' => array(
            'start_year' => array('not_empty' => null, 'numeric' => null, 'max_length' => array(4)),
            'speciality' => array('not_empty' => null),
            'institute' => array('not_empty' => null),
        ),
        'president' => array(
            'period' => array('not_empty' => null),
            'name' => array('not_empty' => null),
            'school' => array('not_empty' => null),
            'jobs' => array('not_empty' => null),
            'term' => array('not_empty' => null),
        ),
        'people_news' => array(
            'title' => array('not_empty' => null),
            'content' => array('not_empty' => null),
        ),
        'vote' => array(
            'title' => array('not_empty' => null),
            'type' => array('not_empty' => null),
        ),
        'apply_mail' => array(
            'username' => array('not_empty' => null, 'min_length' => array(4), 'max_length' => array(16)),
            'password' => array('not_empty' => null, 'min_length' => array(6), 'max_length' => array(16)),
            'password2' => array('not_empty' => null),
        ),
    ),
    'labels' => array(
        'abc' => '索引',
        'bbs_id' => '论坛分类',
        'send_to' => '发送对象',
        'name' => '名称',
        'password2' => '确认密码',
        'password' => '密码',
        'account' => '账号',
        'address' => '地址',
        'realname' => '真实姓名',
        'agreement' => '注册协议',
        'city' => '城市',
        'birthday' => '生日',
        'mobile' => '手机号码',
        'sex' => '性别',
        'title' => '标题',
        'content' => '内容',
        'avatar' => '个人形象',
        'zip' => '邮编',
        'website' => '网站地址',
        'start_at' => '开始时间',
        'expire_at' => '过期时间',
        'company' => '公司单位名称',
        'speciality' => '专业名称',
        'job' => '职位',
        'people' => array(
            'birth' => '出生年月',
            'leave' => '逝世',
            'intro' => '基本介绍',
        ),
        'auth' => array(
            'name' => '姓名',
            'start' => '入学年份',
            'finish' => '毕业年份',
            'depart' => '专业'
        ),
        'register' => array(
            'realname' => '姓名',
            'sex' => '性别',
            'account' => '账号',
            'password' => '密码',
            'agreement' => '注册协议',
            //'city' => '城市',
            'mobile' => '手机号码',
        ),
        'login' => array(
            'account' => '账号',
            'password' => '密码',
        ),
        'category' => array(
            'name' => '名称',
            'order_num' => '排序',
        ),
        'club' => array(
            'title' => '俱乐部负责人头衔',
            'user_id' => '负责人ID',
            'class' => '分类',
        ),
        'event_static' => array(
            'title' => '标题',
            'content' => '内容',
        ),
        'aa_contact' => array(
            'zip' => '邮编',
            'website' => '网站地址',
        ),
        'aa_info' => array(
            'title' => '标题',
            'content' => '内容',
        ),
        'aa_show' => array(
            'title' => '标题',
            'url' => '链接地址',
        ),
        'main_aa' => array(
            'name' => '名称',
            'zip' => '邮编',
            'mail' => '邮箱地址',
        ),
        'msg' => array(
            'send_to' => '发送对象',
            'content' => '内容',
        ),
        'msg_reply' => array(
            'content' => '回复内容',
        ),
        'bubble' => array(
            'content' => '内容',
        ),
        'user_base' => array(
            'city' => '城市',
            'sex' => '性别',
            'mobile' => '手机',
            'tel' => '电话',
            'qq' => 'QQ',
        ),
        'reg_work' => array(
            'city' => '城市',
            'company' => '公司(单位)名称',
            'job' => '职位',
            'start_at' => '起始日期',
            'leave_at' => '结束日期',
        ),
        'user_work' => array(
            'city' => '城市',
            'company' => '公司(单位)名称',
            'industry' => '所属行业',
            'job' => '职位',
            'start_at' => '起始日期',
            'leave_at' => '结束日期',
        ),
        'sys_msg' => array(
            'title' => '标题',
            'content' => '内容',
            'start_at' => '起始时间',
            'expire_at' => '结束时间',
        ),
        'event' => array(
            'aa_id' => '所属校友会',
            'etype' => '活动分类',
            'sign_start' => '报名开始时间',
            'sign_finish' => '报名结束时间',
            'start' => '活动开始时间',
            'finish' => '活动结束时间',
            'sign_limit' => '报名人数限制',
            'content' => '活动内容',
            'address' => '活动地点',
        ),
        'links' => array(
            'name' => '名称',
            'url' => '网址',
        ),
        'content' => array(
            'title' => '分类标题',
            'type' => '内容分类',
            'content' => '内容详情'
        ),
        'content_category' => array(
            'name' => '分类名称',
        ),
        'pubcontent' => array(
            'pub_id' => '所属期刊',
            'col_id' => '所属栏目',
            'title' => '文章标题',
        ),
        'publication' => array(
            'type' => '期刊类型',
            'name' => '期刊名称',
            'issue' => '刊物期号',
        ),
        'elereport' => array(
            'title' => '报刊名称',
            'issue' => '报刊期号',
        ),
        'donate' => array(
            'project' => '捐赠项目',
            'amount' => '金额',
            'donor' => '捐赠单位或个人名称',
            'speciality' => '专业名称',
            'company' => '公司名称',
            'address' => '联系地址',
            'tel' => '电话',
            'email' => '邮件',
            'donate_from' => '捐赠来源',
            'methods' => '捐赠方式',
        ),
        'donate_annual' => array(
            'project' => '捐赠项目名称',
            'donor' => '捐赠集体或个人名称',
            'amount' => '捐赠金额或物品名称',
            'donate_at' => '捐赠日期',
        ),
        'donate_fund' => array(
            'donor' => '捐赠集体或个人名称',
            'donate_at' => '捐赠日期',
            'amount' => '捐赠金额',
        ),
        'classroom' => array(
            'intro' => '班级介绍内容不能为空',
        ),
        'comment' => array(
            'content' => '内容',
        ),
        'bbs_focus' => array(
            'bbs_unit_id' => '论坛版块',
            'numeric' => '默认值',
        ),
        'bbs_post' => array(
            'bbs_id' => '话题板块',
            'title' => '标题',
            'content' => '内容',
        ),
        'news_category' => array(
            'name' => '分类名称',
            'order_num' => '分类排序',
        ),
        'news_special' => array(
            'name' => '专题名称',
        ),
        'news' => array(
            'title' => '标题',
            'content' => '内容',
        ),
        'new_classroom' => array(
            'start_year' => '入学年份',
            'speciality' => '专业名称',
            'institute' => '学院(系)名称',
        ),
        'abook' => array(
            'mobile' => '手机',
            'address' => '地址',
        ),
        'card' => array(
            'realname' => '姓名',
            'mobile' => '手机号码',
        ),
        'president' => array(
            'period' => '时期',
            'name' => '姓名',
            'school' => '所在学校',
            'jobs' => '职位',
            'term' => '任期',
        ),
        'people_news' => array(
            'title' => '标题',
            'content' => '内容',
        ),
        'vote' => array(
            'title' => '调查名称',
            'type' => '类型',
        ),
        'apply_mail' => array(
            'username' => '登录名',
            'password' => '密码',
            'password2' => '确认密码',
        ),
    ),
        )
//445
?>