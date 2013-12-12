<?php

//其他内容管理
class Controller_Admin_Content extends Layout_Admin {

    function before() {
	parent::before();
	$leftbar_links = array(
	    'admin_content/index' => '内容管理',
	    'admin_content/form' => '添加新内容',
	    'admin_content/category' => '分类管理',
	    'admin_links/index' => '友情链接',

	);

	//内容分类
	$content_type = Doctrine_Query::create()
			->from('ContentCategory')
			->orderBy('order_num,id')
			->fetchArray();
	View::set_global('content_type', $content_type);
	$this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    //内容管理首页
    function action_index() {
	$type = Arr::get($_GET, 'type');
	$q = Arr::get($_GET, 'q');
	$content = Doctrine_Query::create()
			->select('c.id,c.title,c.create_at,c.is_system,c.update_at,c.redirect,t.name as cname')
			->from('Content c')
			->leftJoin('c.ContentCategory t')
			->orderBy('c.order_num,c.create_at DESC,c.id DESC');

	if ($type) {
	    $content->where('c.type=?', $type);
	    $this->_sess->set('sess_content_type',$type);
	}

	if ($q) {
	    $content->where('title LIKE ?', array('%' . $q . '%'));
	}

	$total_content = $content->count();

	$pager = Pagination::factory(array(
		    'total_items' => $total_content,
		    'items_per_page' => 15,
		    'view' => 'pager/common',
		));

	$view['type'] = $type;
	$view['q']=$q;
	$view['pager'] = $pager;
	$view['content'] = $content->offset($pager->offset)
			->limit($pager->items_per_page)
			->fetchArray();

	$this->_title('内容管理');
	$this->_render('_body', $view);
    }

    //添加或修改内容
    function action_form() {
	$id = Arr::get($_GET, 'id', 0);
	$type= Arr::get($_POST,'type');
	$view['sess_content_type']= $this->_sess->get('sess_content_type');
	$view['err']='';
	$img_path = '';
	$file_name = date("YmdHis");
	$content = Doctrine_Query::create()
			->from('Content')
			->where('id = ?', $id)
			->fetchOne();
	$view['content'] = $content;
	if($_POST){

		//如果存在附件
                if($_FILES['file']['size'] > 0) {
                     //上传的图片附件
                    $valid = Validate::factory($_FILES);
                    $valid->rules('file', Model_Content::$up_rule);
                    if( ! $valid->check()) {
                        $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                    } else {
                        // 处理图片附件
                        $path = DOCROOT.Model_Content::FILE_PATH;
                        Upload::save($_FILES['file'], $file_name, $path);
                        //原尺寸
                        Image::factory($path.$file_name)
                                ->resize(null,null, Image::NONE)
                                ->save($path.$file_name.'.jpg');

			 //缩略图
		         Image::factory($path.$file_name)
			    ->resize(150, 113, Image::NONE)
			    ->save($path.$file_name . '_s.jpg');
			$img_path = URL::base() . Model_Content::FILE_PATH . $file_name . '_s.jpg';
                        unlink($path.$file_name);
                    }
                }

            $valid = Validate::setRules($_POST, 'content');
            $post = $valid->getData();
            if( ! $valid->check()){
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {

		if ($img_path) {
		    $post['img_path'] = $img_path;
		}
                // 添加或修改内容
                if($content){
                    unset($post['id']);
		    $post['user_id'] = $this->_sess->get('id');
		    $post['update_at'] = date('Y-m-d H:i:s');
                    $content->synchronizeWithArray($post);
                    $content->save();
                } else {
                    $content = new Content();
		    $post['user_id'] = $this->_sess->get('id');
		    $post['update_at'] = Arr::get($_POST,'create_at');
                    $content->fromArray($post);
                    $content->save();
                }
		//记住上次分类id
		$this->_sess->set('sess_content_type',Arr::get($_POST, 'type'));

                // 处理完毕后刷新页面
                $this->request->redirect('admin_content/index?type='.$type);
            }
        }

	$this->_render('_body',$view);
    }

    //添加或修改内容分类
    function action_category() {
	$id = Arr::get($_GET, 'id', 0);
	$view['err']='';
	$category = Doctrine_Query::create()
			->from('ContentCategory')
			->where('id = ?', $id)
			->fetchOne();

	$view['category'] = $category;

	if($_POST){
            $valid = Validate::setRules($_POST, 'content_category');
            $post = $valid->getData();
            if( ! $valid->check()){
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 添加或修改内容
                if($category){
                    unset($post['id']);
                    $category->synchronizeWithArray($post);
                    $category->save();
                } else {
                    $category = new ContentCategory();
                    $category->fromArray($post);
                    $category->save();
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_content/category');
            }
        }
	$this->_render('_body',$view);
    }

    #删除内容
    function action_del(){
	$this->auto_render = FALSE;

	$id = Arr::get($_GET, 'cid');
	$content = Doctrine_Query::create()
			->from('Content')
			->where('id = ?', $id)
			->fetchOne();

	if ($content) {
	    $content->delete();
	}

	@unlink($content['img_path']);

    }
    
}

?>
