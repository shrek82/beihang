<?php

class Controller_Help extends Layout_Main {

    function before() {
        parent::before();
    }

    function action_index() {
        $type = Arr::get($_GET, 'type');
        $id = Arr::get($_GET, 'id');

        $category = Doctrine_Query::create()
                ->from('ContentCategory')
                ->where('id=?', $type)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $menus = Doctrine_Query::create()
                ->select('id,title,redirect,type')
                ->from('Content')
                ->where('type=?', $type)
                ->orderBy('order_num ASC')
                ->fetchArray();

        $content = Doctrine_Query::create()
                ->from('Content')
                ->where('id=?', $id)
                ->fetchOne();

        if (!$content) {
            $this->request->redirect('main/notFound');
        }

        $this->_title($content['title']);
        $this->_render('_body', compact('category', 'menus', 'content'));
    }

    //找回账号或密码
    function action_forgetAccount() {
        $type = Arr::get($_POST, 'type');
        $q = urldecode(trim(Arr::get($_POST, 'q')));
        $addr = Arr::get($_GET, 'addr');
        $uid = Arr::get($_GET, 'uid');
        $enc = Arr::get($_GET, 'enc');
        $newpassword = Arr::get($_POST, 'newpassword');
        $newpassword2 = Arr::get($_POST, 'newpassword2');
        $user = null;
        $err = null;
        $success = null;

        //处理提交的表单
        if ($_POST && $q) {
            //什么都不记得了,根据姓名
            if ($type == 'name') {
                $user = Doctrine_Query::create()
                        ->select('id,nick,account,realname,sex,city,education,speciality,start_year')
                        ->from('User')
                        ->where('realname=?', $q)
                        ->fetchArray();
            }

            //根据邮箱地址找回密码
            elseif ($type == 'email') {

                $user = Doctrine_Query::create()
                        ->select('*')
                        ->from('User')
                        ->where('account=?', $q)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
                //发送重置密码邮件
                if ($user) {
                    $mailer = new Model_Mailer('first');
                    $mailer->resetPassword($user['realname'], $user['account'], $user['id']);
                    $success = '恭喜您，密码发送成功，请登陆您的邮箱重新设置密码，谢谢!';
                } else {
                    $err = '很抱歉，没有找到“' . $q . '”账号，请返回重试，谢谢！';
                }
            }
        }

        //处理来自邮件的密码重置
        elseif ($addr && $uid && $enc) {
            if ($enc == md5($addr . Model_User::URLKEY)) {
                //保存新密码
                if ($_POST && $newpassword) {
                    $user = Doctrine_Query::create()
                            ->select('*')
                            ->from('User')
                            ->where('id=?', $uid)
                            ->addWhere('account=?', $addr)
                            ->fetchOne();
                    if ($user) {
                        $post['password'] = md5($newpassword);
                        $user->fromArray($post);
                        $user->save();
                        $success = '恭喜您，密码修改成功！';
                    } else {
                        $err = '很抱歉，账号不存在，请返回查找账号或重试，谢谢！';
                    }
                }
            }
            //验证失败
            else {
                $err = '验证码错误，请检查地址是否完整，谢谢!';
            }
        }

        $this->_render('_body', compact('user', 'q', 'type', 'addr', 'uid', 'enc', 'err', 'success'));
    }

    //档案查询
    function action_file() {

        $user_id = $this->_sess->get('id');
        if (!$user_id) {
            $this->_redirect(('user/login'));
            exit;
        }

        if ($_POST) {
            $name = Arr::get($_POST, 'realname');
            $begin_year = Arr::get($_POST, 'begin_year');
            $graduation_year = Arr::get($_POST, 'graduation_year');
            $speciality = Arr::get($_POST, 'speciality');
            $institute = Arr::get($_POST, 'institute');

            if ($name) {
                $alumni = new Model_Alumni();
                $alumni->conn();
                $files = $alumni->get(array(
                    'realname' => $name,
                    'start_year' => $begin_year,
                    'graduation_year' => $graduation_year,
                    'speciality' => $speciality,
                    'institute' => $institute,
                ));
                $view['files'] = $files;
            } else {
                $view['files'] = '';
            }

            echo View::factory('help/filesearch', $view);
        } else {
            $this->_render('_body');
        }
    }

    //查看档案信息
    function action_fileView() {
        $id = Arr::get($_GET, 'id');
        $view['user'] = Doctrine_Query::create()
                ->from('Alumni')
                ->where('id =?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        echo View::factory('help/fileView', $view);
    }

    function action_test() {
        
        $alumni = new Model_Alumni();
        $alumni->conn();
        $files = $alumni->get(array(
            'realname' => '李口',
            'start_year' => 2000,
            'graduation_year' => null,
            'speciality' => '成人教育'
        ));
        
        echo Kohana::debug($files);
    }

}

?>
