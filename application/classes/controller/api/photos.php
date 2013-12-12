<?php

//用户接口
class Controller_Api_Photos extends Layout_Mobile {

    function before() {
        parent::before();
    }

    function action_index() {

        $limit = $this->getParameter('limit', 8);
        $page = $this->getParameter('page', 1);
        $max_id = $this->getParameter('max_id');
        $since_id = $this->getParameter('since_id');
        $cat = $this->getParameter('cat');
        $offset = ($page - 1) * $limit;

        $albums = Doctrine_Query::create()
                ->from('Album a')
                ->select('a.id,a.pic_num,a.name')
                ->addSelect('(SELECT p.img_path FROM Pic p WHERE p.album_id = a.id ORDER BY p.id DESC LIMIT 1) AS cover_path');

        if ($this->_uid) {
            $albums->whereIn('a.aa_id', Model_User::aaIds($this->_uid));
        } else {
            $albums->where('a.aa_id>0');
        }

        if ($since_id) {
            $albums->addWhere('a.id>?', $since_id);
        }

        if ($max_id) {
            $albums->addWhere('a.id<?', $max_id);
        }

        $albums->addWhere('a.pic_num>=1');

        $albums = $albums->offset($offset)
                ->limit($limit)
                ->orderBy('a.update_at DESC')
                ->fetchArray();

        $data = array();
        if (count($albums) > 0) {
            foreach ($albums as $key => $a) {
                $data[$key]['album_id'] = $a['id'];
                $data[$key]['pics_count'] = $a['pic_num'];
                $name = preg_replace('/(\d+)-(\d+)-(\d+)/', '', $a['name']);
                $name = preg_replace('/(\d+)(\d+)(\d+)/', '', $name);
                $data[$key]['name'] = $name;
                //老地址
                if (strstr($a['cover_path'], 'resize')) {
                    $cover_path = str_replace('resize/', 'bmiddle/', $a['cover_path']);
                    if (!file_exists($cover_path)) {
                        $cover_path = $a['cover_path'];
                    }
                } elseif (strstr($a['cover_path'], '_mini')) {
                    $cover_path = str_replace('_mini', '_thumbnail', $a['cover_path']);
                } else {
                    $cover_path = $a['cover_path'];
                }
                $data[$key]['cover_path'] = $this->_siteurl . '/' . $cover_path;
            }
        }
        $this->response($data, 'list', null);
    }

    function action_list() {
        $album_id = $this->getParameter('album_id');
        $photos = Doctrine_Query::create()
                ->select('p.*')
                ->from('Pic p')
                ->where('p.album_id = ?', $album_id)
                ->orderBy('p.id ASC')
                ->fetchArray();

        $album = Doctrine_Query::create()
                ->from('Album')
                ->where('id=?', $album_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $data = array();
        if (count($photos) > 0) {
            foreach ($photos as $key => $p) {
                $pics[$key]['nid'] = 'nid' . $key + 1;
                $data[$key]['pic_id'] = $p['id'];
                $data[$key]['title'] = $p['name'];
                $data[$key]['intro'] = empty($p['intro'])?$album['name']:$p['intro'];
                $data[$key]['thumbnail_pic'] = $this->_siteurl . '/' . $p['img_path'];
                $bmiddle_path = str_replace('resize/', '', $p['img_path']);
                $bmiddle_path = str_replace('_mini', '_bmiddle', $bmiddle_path);
                $data[$key]['bmiddle_pic'] = $this->_siteurl . '/' . $bmiddle_path;
                $original_pic = str_replace('_bmiddle', '', $bmiddle_path);
                $data[$key]['original_pic'] = $this->_siteurl . '/' . $original_pic;
            }
        }
        else{
                $pics[0]['nid'] = 'nid1';
                $data[0]['pic_id'] = 0;
                $data[0]['title'] = 'none';
                $data[0]['intro'] = '暂时还没有任何图片，快来上传一张吧！';
                $data[0]['thumbnail_pic'] = $this->_siteurl . '/' . 'static/images/nophoto.jpg';
                $bmiddle_path = $data[0]['thumbnail_pic'];
                $data[0]['bmiddle_pic'] = $data[0]['thumbnail_pic'];
                $data[0]['original_pic'] = $data[0]['thumbnail_pic'];
        }
        $this->response($data, 'list', null);
    }

}

?>
