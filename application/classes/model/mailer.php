<?php

/**
  +-----------------------------------------------------------------
 * 名称：邮件发送模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
 * 网站名称略有不同
  +-----------------------------------------------------------------
 */
Candy::import('swiftMailer');

class Model_Mailer {

    protected $_transport;
    protected $_config;
    protected $_set;

    function __construct($set) {
        $this->_set = $set;
        $config = Kohana::config('email.' . $set);
        $this->_config = $config;

        $transport = Swift_SmtpTransport::newInstance($config['smtp'])
                ->setPort($config['port'])
                ->setUsername($config['username'])
                ->setPassword($config['password']);

        if (isset($config['enc'])) {
            $transport->setEncryption($config['enc']);
        }

        $this->_transport = $transport;
    }

    //发送激活邮件
    function userActive($realname, $address) {

        $config = Kohana::config('config.base');

        $body = View::factory('inc/register/active_account', array(
                    'config' => $config,
                    'realname' => $realname,
                    'account' => $address,
                ));

        $message = Swift_Message::newInstance()
                ->setSubject($config['sitename'].'帐号激活邮件')
                ->setFrom(array($this->_config['from'] => '系统邮件'))
                ->setTo(array($address => $realname))
                ->setBody($body, 'text/html');
        try {
            Swift_Mailer::newInstance($this->_transport)->send($message);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //发送密码重置邮件
    function resetPassword($realname, $address, $user_id) {

        $base_config = Kohana::config('config')->base;
        $site_name = $base_config['sitename'];

        $body = View::factory('help/reset_password', array(
                    'realname' => $realname,
                    'uid' => $user_id,
                    'account' => $address,
                    'enc' => md5($address . Model_User::URLKEY),
                ));

        $message = Swift_Message::newInstance()
                ->setSubject('' . $realname . '您好！这是您在' . $site_name . '的密码重置邮件')
                ->setFrom(array($this->_config['from'] => '系统邮件'))
                ->setTo(array($address => $realname))
                ->setBody($body, 'text/html');
        try {
            Swift_Mailer::newInstance($this->_transport)->send($message);
        } catch (Exception $e) {
            //echo $e->getMessage();
        }
    }

    //发送注册邀请
    function sendRegInvite($sender, $address, $url) {

        $base_config = Kohana::config('config')->base;
        $site_name = $base_config['sitename'];

        $body = View::factory('inc/register/invite', array(
                    'sender' => $sender,
                    'address' => $address,
                    'url' => $url,
                ));

        $message = Swift_Message::newInstance()
                ->setSubject($sender . '邀请您加入' . $site_name)
                ->setFrom(array($this->_config['from'] => '系统邮件'))
                ->setTo(array($address => $sender))
                ->setBody($body, 'text/html');
        try {
            Swift_Mailer::newInstance($this->_transport)->send($message);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}