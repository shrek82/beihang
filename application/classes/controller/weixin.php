<?php

//微信开发者接口
class Controller_Weixin extends Layout_Main {

    //微信服务器验证
    public $signature;
    public $timestamp;
    public $nonce;
    public $echostr;

    function before() {
        parent::before();
        $this->template = false;
        $this->signature = Arr::get($_GET, 'signature');
        $this->timestamp = Arr::get($_GET, 'timestamp');
        $this->nonce = Arr::get($_GET, 'nonce');
        $this->echostr = Arr::get($_GET, 'echostr');
    }

    #查询

    function action_index() {
        //用户请求
        //
        //文本消息-----------------------------------------
        $ToUserName = Arr::get($_POST, 'signature');
        //发送方帐号（一个OpenID）
        $FromUserName = Arr::get($_POST, 'FromUserName');
        //消息创建时间 （整型）
        $CreateTime = get($_POST, 'CreateTime');
        //消息类型，text说明是文本消息
        $MsgType = get($_POST, 'MsgType');
        //消息id，64位整型
        $MsgId = get($_POST, 'MsgId');

        //文本消息内容(MsgType=text)
        $Content = get($_POST, 'Content');

        //图片消息(MsgType=image)
        $PicUrl = get($_POST, 'PicUrl');

        echo '<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>12345678</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[content]]></Content>
 <FuncFlag>0</FuncFlag>
 </xml>';
    }

}