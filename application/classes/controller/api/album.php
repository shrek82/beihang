<?php

//论坛
class Controller_Api_Album extends Layout_Mobile {

    function before() {
        parent::before();
    }

    function action_index() {

    }

    //上传照片
    function action_post() {
        $this->checkLogin();
        $album_id = $this->getParameter('album_id');
        $content = $this->getParameter('content');
        //存在上传图片
        $img_path = null;
        $img_name = null;
        $thumbnail = null;
        $bmiddle = null;
        $original = null;
        if ($_FILES AND $_FILES['upload_file']['size'] > 0) {
            $upload_images = Common_Upload::pic($_FILES['upload_file'], $this->_uid);
            //上传失败
            if (isset($upload_images['error']) AND $upload_images['error']) {
                $this->error($upload_images['error']);
            }
            //上传成功
            else {
                $img_path = isset($upload_images['images']['mini']) ? $upload_images['images']['mini'] : false;
                $thumbnail = isset($upload_images['images']['thumbnail']) ? $upload_images['images']['thumbnail'] : false;
                $bmiddle = isset($upload_images['images']['bmiddle']) ? $upload_images['images']['bmiddle'] : false;
                $original = isset($upload_images['images']['original']) ? $upload_images['images']['original'] : false;
                $img_name = isset($upload_images['img_name']) ? $upload_images['img_name'] : 'photo';
            }
        } else {
            $this->error('没有上传如何照片信息');
        }

        if ($img_path AND $img_name) {
            $pic = new Pic();
            $pic->user_id = $this->_uid;
            $pic->album_id = $album_id;
            $pic->img_path = $img_path;
            $pic->name = $img_name;
            $pic->intro = trim($content);
            $pic->upload_at = date('Y-m-d H:i:s');
            $pic->save();

            if ($pic->id) {
                $back = array();
                $back['id'] = $pic->id;
                $back['album_id'] = $album_id;
                $back['uid'] = $this->_uid;
                $back['intro'] = trim($content);
                $back['name'] = $img_name;
                $back['mini_pic'] = $this->_siteurl . '/' . $img_path;
                $back['thumbnail_pic'] = $this->_siteurl . '/' . $thumbnail;
                $back['bmiddle_pic'] = $this->_siteurl . '/' . $bmiddle;
                $back['original_pic'] = $this->_siteurl . '/' . $original;
                $back['post_date'] = date('Y-m-d H:i:s');
                $this->response($back, 'success', 'success');
                Model_Album::updatePicNum($album_id);
            } else {
                $this->error('保存失败，请重试或与管理员联系');
            }
        }
    }

}

?>
