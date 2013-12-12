<?php

/**
  +-----------------------------------------------------------------
 * 名称：手机API控制器
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:44
  +-----------------------------------------------------------------
 */
class Layout_Mobile extends Kohana_Controller_Template {

    public $template = 'xhtml';
    public $_tokenKey = 'zuaa_Api@zju.edu.cn';
    public $_siteurl;
    public $_doc;
    public $_apikey;
    public $_uid;
    public $_user;
    public $_clients;
    public $_error;
    public $_format;
    public $_cache;

    //预设变量
    function before() {
        parent::before();
        $this->_siteurl = 'http://' . $_SERVER['HTTP_HOST'];
        $this->auto_render = false;
        $apikeys = Kohana::config('apikeys');

        //apikey
        $this->_apikey = $this->getParameter('apikey');

        //输出格式
        $this->_format = strtolower($this->getParameter('format', 'xml'));

        //输出api文档说明
        $this->_doc = strtolower($this->getParameter('doc'));

        //接收请求参数
        $this->_uid = $this->getParameter('uid');

        //令牌
        $token = $this->getParameter('token');

        //apikey合法验证
        if (empty($this->_apikey)) {
            $this->error('apikey不能为空');
        }

        if (!isset($apikeys[$this->_apikey])) {
            $this->error('错误的apikey');
        }
        //客户端类型
        else {
            $this->_clients = $apikeys[$this->_apikey]['client'];
        }

        //接收内容来自登录用户
        if ($this->_uid AND $token) {
            $user = Db_User::getInfoById($this->_uid);
            //存在用户数据
            if ($user) {
                //验证当前令牌
                $tempToken = Model_User::createToken($user['id'], $user['password']);
                $validated = $token == $tempToken ? True : False;
                if ($validated) {
                    $this->_uid = $user['id'];
                    $this->_user = $user;
                } else {
                    $this->error('无效的用户令牌，可能是密码已被修改，建议重新登录以解决。');
                }

                //实时更新最后登录时间
                if (strtotime('now') - strtotime($user['login_time']) >= 60) {
                    Db_User::updateField($user['id'], array('login_time' => date('Y-m-d H:i:s'), 'login_clients' => $this->_clients, 'login_num' => $user['login_num'] + 1));
                }
            }
            //没有用户数据
            else {
                $this->error('用户不存在或被删除');
            }
        }
        $this->_cache = Cache::instance(Layout_Db::CACHE_GROUP);
    }

    //输出到客户端(支持json或xml格式)
    function response($data, $xml_element = null, $json_element = null) {
        $xml_data = array();
        $json_data = array();
        if ($this->_doc) {
            echo View::factory('api/doc', array('data' => $data));
        }
        //返回json表示
        elseif ($this->_format == 'json') {
            if ($json_element) {
                $json_data[$json_element] = $data;
            } else {
                $json_data = $data;
            }
            echo json_encode($json_data);
        }
        //返回xml
        else {
            $this->request->headers['Content-Type'] = 'text/xml';
            $this->request->headers['charset'] = 'utf-8';
            Candy::import('arrayToxml');
            $xml = new XmlUtil();
            if ($xml_element) {
                $xml_data[$xml_element] = $data;
            } else {
                $xml_data = $data;
            }
            echo $xml->getXML($xml_data);
        }
    }

    //获取提交参数
    public function getParameter($var, $default = null) {
        $value = Arr::get($_GET, $var);
        if ($value) {
            return $value;
        } else {
            return Arr::get($_POST, $var, $default);
        }
    }

    //错误输出
    public function error($message) {
        if(!is_array($message)){
            $message=strip_tags($message);
        }
        $this->response(array('error' => $message));
        exit;
    }

    //检查是否登录
    public function checkLogin() {
        if (!$this->_uid) {
            $this->error('您还没有登录，请登录后报名');
        }
    }

}

?>
