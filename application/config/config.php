<?php

//网站参数
return array(
    'base' => array(
        'id' => 'beihang',
        'university_name' => '北航',
        'orgname' => '北航校友会',
        'sitename' => '北航校友网',
        'alumni_name' => '北航校友',
        'domain_name' => 'xyh.buaa.edu.cn/',
        'manager_mail' => 'xyhbuaa@buaa.edu.cn',
        'manager_name'=>'王老师',
        'manager_tel'=>'010-82338260'
    ),
    'modules' => array(
        'invite' => True,
        'news_contribute' => True,
        'publication_contribute' => True,
        'register_mail' => False,
        'binding' => False,
    ),
    'reg' => array(
        'all_step' => 5,
        'is_verify_role' => '校友(未审核)'
    ),
);
?>