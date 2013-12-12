<?php
/* * *
 * @project:socket类
 * @license:GPL
 * @description:PHP Socket协议异步通信
 * @file:socket.class.php
 * @last modified :
 * * */

class sockets {

    public $host; //通信地址
    public $port; //通信端口
    public $limitTime = 0; //连接超时时间
    public $backlog = 3; //请求队列中允许的最大请求数
    private $socket = null;
    private $result = null;
    private $spawn = null;
    private $input = null;

    //构造函数
    public function __construct() {
        set_time_limit($this->limitTime);
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
    }

    //创建Socket连接,监听外部连接
    public function socket_bind_listen() {
        $this->result = socket_bind($this->socket, $this->host, $this->port) or die("Could not bind to socket\n");
        $this->result = socket_listen($this->socket, $this->backlog) or die("Could not set up socket listener\n");
    }

    //接受请求连接,调用socket处理信息
    public function accept_client() {
        $this->spawn = socket_accept($this->socket) or die("Could not accept incoming connection\n");
        $msg = "Welcome to the Test Server";
        socket_write($this->spawn, $msg, strlen($msg));
    }

    // 读取客户端输入
    public function read_client() {
        $this->input = socket_read($this->spawn, 2048, PHP_NORMAL_READ) or die("Could not read input\n");
    }

    // 处理客户端输入并返回数据
    public function write_client() {
        socket_write($this->spawn, $this->input, strlen($this->input)) or die("Could not write output\n");
    }

    //析构函数
    public function __destruct() {
        socket_close($this->spawn);
        socket_close($this->socket);
    }
}

?>
