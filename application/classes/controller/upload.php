<?php

class Controller_Upload extends Layout_Main {

    public function before() {
        parent::before();
    }

    //通过ueditor image swf上传附件
    function action_uploadAttachedfile() {

        $this->auto_render = False;

        //是否有权限
        //$is_upload = $this->userPermissions('uploadpic', true);
        //上传配置
        $config = array(
            "fileType" => array(".rar", ".zip", ".doc", ".docx", '.xls', '.wps', ".pdf", ".txt", ".swf", ".wmv", '.jpg', '.png', '.jpeg', '.mp3', '.ppt', '.pptx', '.avi', '.rmvb', '.wmv', '.flv', '.rm', '.psd', '.ipa'), //文件允许格式
            'fileSize' => 50, //文件大小限制，单位M
        );

        //文件上传状态,当成功时返回SUCCESS，其余值将直接返回对应字符窜并显示在图片预览框，同时可以在前端页面通过回调函数获取对应字符窜
        $state = "SUCCESS";

        //自动创建目录
        if (!is_dir('static/upload/attached/' . date('Ym'))) {
            mkdir('static/upload/attached/' . date('Ym'), 0777);
        }

        if ($_FILES) {
            $clientFile = $_FILES["Filedata"]; //"Filedata是swfupload默认的文件域"
            $old_file_name = $clientFile['name'];
            $error = false;

            //格式文件验证
            $file_extend = strtolower(strrchr($clientFile["name"], '.'));
            if (!in_array($file_extend, $config['fileType'])) {
                $error = "不支持的文件类型！";
            }

            //验证文件大小
            $file_size = 1024 * 1024 * $config['fileSize'];
            if ($clientFile["size"] > $file_size) {
                $error = "文件大小超出限制！";
            }

            //保存目录
            $path = 'static/upload/attached/' . date('Ym') . '/';

            //文件名称(无后缀)
            $file_name = date("YmjHis") . rand(10, 1000);

            //上传后未处理的文件路径
            $upload_file_path = $path . $file_name . $file_extend;

            //可以上传
            if (!$error) {
                Upload::save($clientFile, $file_name . $file_extend, $path);
            } else {
                $state = $error;
            }

            $title = '';

            //向浏览器返回数据json数据
            $file = str_replace('../', '', $upload_file_path);  //为方便理解，替换掉所有类似../和./等相对路径标识
            echo "{'state':'" . $state . "','url':'" . $upload_file_path . "','fileType':'" . $file_extend . "','fileName':'" . $old_file_name . "','original':'" . $old_file_name . "'}";
        } else {
            echo "{'state':'没有上传任何文件','url':'null','fileType':'null'}";
        }
    }

    //通过ueditor image swf上传图片
    function action_uploadAttachedImage() {

        $this->auto_render = False;
        $album_id = Arr::get($_POST, 'album_id');

        if (!$this->_uid) {
            $error = '您木有登陆～';
            echo "{'state':'" . $error . "','url':'null'}";
            exit;
        }

        //文件上传状态
        $state = "SUCCESS";

        //图片title
        $title = htmlspecialchars(Arr::get($_POST, 'pictitle'));

        //是否添加水印
        $add_watermark = Arr::get($_POST, 'add_watermark') == 'yes' ? true : false;

        $return_file_path = false;

        if ($_FILES AND $_FILES['picdata']['size'] > 0) {

            //利用公共方法上传4种规格图片
            $upload_images = Common_Upload::pic($_FILES['picdata'], $this->_uid, $add_watermark);

            if (isset($upload_images['images']['bmiddle'])) {
                $return_file_path = $upload_images['images']['bmiddle'];
            } elseif (isset($upload_images['error'])) {
                $state = $upload_images['error'];
            } else {
                $state = '上传失败';
            }
        } else {
            $state = '没有选择文件';
        }

        //图片名称
        $title = str_replace('.jpg', '', strtolower($title));
        $title = str_replace('.jepg', '', $title);
        $title = str_replace('.gif', '', $title);
        $title = str_replace('.bmp', '', $title);

        //向浏览器返回数据json数据
        //$file = str_replace('../', '', $return_file_path);  //为方便理解，替换掉所有类似../和./等相对路径标识
        echo "{'url':'" . $return_file_path . "','title':'" . $title . "','state':'" . $state . "'}";
    }

    //处理来自uploadify的图片并返回
    function action_uploadify() {

        $this->auto_render = False;
        $this->_uid = Arr::get($_POST, 'uid');
        $album_id = Arr::get($_POST, 'album_id');
        $user_id = $this->_uid;


        if (!$user_id) {
            echo json_encode(array('status' => 0, 'error' => '您还没有登录'));
            exit;
        }

        //文件上传状态
        $state = "SUCCESS";

        //图片title
        $title = htmlspecialchars(Arr::get($_POST, 'pictitle'));

        $return_file_path = false;

        if ($_FILES AND $_FILES['Filedata']['size'] > 0) {

            $original_name = $_FILES["Filedata"]['name'];
            $original_name = str_replace('.jpg', '', $original_name);
            $original_name = str_replace('.jpeg', '', $original_name);
            $original_name = str_replace('.JPG', '', $original_name);
            $original_name = str_replace('.JPEG', '', $original_name);

            //利用公共方法上传4种规格图片
            $upload_images = Common_Upload::pic($_FILES['Filedata'], $user_id, false);

            if (isset($upload_images['images']['bmiddle'])) {
                $thumbnail_file_path = $upload_images['images']['mini'];
                $return_file_path = $upload_images['images']['bmiddle'];
                $original_name = $upload_images['name'];
            } elseif (isset($upload_images['error'])) {
                $state = $upload_images['error'];
            } else {
                $state = '上传失败';
            }
        } else {
            $state = '没有选择文件';
        }

        //过滤后缀来获取图片名称
        $title = str_replace('.jpg', '', strtolower($title));
        $title = str_replace('.jepg', '', $title);
        $title = str_replace('.gif', '', $title);
        $title = str_replace('.bmp', '', $title);

        //添加照片
        $pic = new Pic();
        $pic->user_id = $user_id;
        $pic->album_id = $album_id;
        $pic->name = $original_name;
        $pic->img_path = $upload_images['images']['mini'];
        $pic->upload_at = date('Y-m-d H:i:s');
        $pic->save();
        Model_Album::updatePicNum($album_id);

        if ($state == 'SUCCESS') {
            echo json_encode(array('status' => 1, 'big_path' => $return_file_path, 'smail_path' => $thumbnail_file_path, 'width' => '150', 'height' => '150', 'pic_id' => $pic->id));
        } else {
            echo json_encode(array('status' => 0, 'error' => $state));
        }
    }

    function action_frame() {

        $this->auto_render = False;
        $msg = Arr::get($_GET, 'msg');
        $view['msg'] = $msg;
        $view['error'] = false;
        $view['file_path'] = null;
        $view['file_extend'] = null;
        $view['fileName'] = null;

        //上传配置
        $config = array(
            "fileType" => array(".rar", ".zip", ".doc", ".docx", '.xls', '.wps', ".pdf", ".txt", ".swf", ".wmv", '.jpg', '.png', '.jpeg', '.mp3', '.ppt', '.pptx', '.avi', '.rmvb', '.wmv', '.flv', '.rm', '.psd', '.ipa'), //文件允许格式
            'fileSize' => 50, //文件大小限制，单位M
        );

        //自动创建目录
        if (!is_dir('static/upload/attached/' . date('Ym'))) {
            mkdir('static/upload/attached/' . date('Ym'), 0777);
        }

        //post
        if ($_POST AND $_FILES) {
            $clientFile = $_FILES["file"];
            $old_file_name = $clientFile['name'];
            $error = false;

            //格式文件验证
            $file_extend = strtolower(strrchr($clientFile["name"], '.'));
            if (!in_array($file_extend, $config['fileType'])) {
                $error = "不支持的文件类型！";
            }

            //验证文件大小
            $file_size = 1024 * 1024 * $config['fileSize'];
            if ($clientFile["size"] > $file_size) {
                $error = "文件大小超出限制！";
            }

            //保存目录
            $path = 'static/upload/attached/' . date('Ym') . '/';

            //文件名称(无后缀)
            $file_name = date("YmjHis") . rand(10, 1000);

            //上传后未处理的文件路径
            $upload_file_path = $path . $file_name . $file_extend;

            //可以上传
            if (!$error) {
                Upload::save($clientFile, $file_name . $file_extend, $path);
            }

            //向浏览器返回数据
            $view['error'] = $error;
            $view['file_path'] = $upload_file_path;
            $view['file_extend'] = $file_extend;
            $view['fileName'] = $old_file_name;
        } elseif ($_POST) {
            $view['error'] = '没有上传任何文件';
        }

        echo View::factory('upload/frame', $view);
    }

    //通过frame嵌套上传图片
    function action_frameimg() {
        $this->auto_render = False;
        $view['error'] = '';
        $view['file_path'] = '';
        $view['file_extend'] = '';

        //返回绝对路径
        $prefix_path = Arr::get($_GET, 'prefix_path', '');
        $view['prefix_path'] = $prefix_path;

        //自定义上传规格说明
        $msg = Arr::get($_GET, 'msg');
        $view['msg'] = $msg;

        //重新设置缩略图大小
        $resize = Arr::get($_GET, 'resize');
        $view['resize'] = $resize;
        $resize = $this->getnewsize($resize);

        //自定义返回图片名称
        $return_file_size = Arr::get($_GET, 'return_file_size', 'mini');
        $view['return_file_size'] = $return_file_size;

        if ($_FILES AND $_FILES['file']['size'] > 0) {
            $upload_images = Common_Upload::pic($_FILES['file'], $this->_uid, false, $resize);
            //上传失败
            if (isset($upload_images['error']) AND $upload_images['error']) {
                $view['error'] = $upload_images['error'];
            }
            //上传成功
            else {
                $view['file_path'] = isset($upload_images['images'][$return_file_size]) ? $upload_images['images'][$return_file_size] : false;
            }
        } else {
            
        }
        echo View::factory('upload/frameimg', $view);
    }

    //解析自定义图图片规格，返回二维数组
    //resize=original_1000_600,bmiddle_600_400
    public function getnewsize($resize) {
        $newsize = array();
        if ($resize) {
            $resize = explode(',', $resize);
            foreach ($resize AS $size) {
                $asize = explode('_', $size);
                if (count($asize) == 3) {
                    $newsize[$asize[0]] = array('width' => $asize[1], 'height' => $asize[2]);
                }
            }
        }
        return $newsize;
    }

}
