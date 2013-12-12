<?php
class Controller_Admin_Links extends Layout_Admin{
    function  before() {
	parent::before();
	   $leftbar_links = array(
            'admin_links/index' => '链接管理',
            'admin_links/add' => '添加新链接',
        );

	   $type_links=array(
	       '1'=>'校内链接',
	       '2'=>'校外链接',
	   );

        View::set_global('type_links',$type_links);
        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    #文字链接
    function action_index(){
	$type=Arr::get($_GET, 'type');
	$is_logo=  Arr::get($_GET,'is_logo');
	$links=Doctrine_Query::create()
	    ->from('Links')
	    ->orderBy('order_num,id DESC');

	if($is_logo){
         $links->where('is_logo=?',1);
	}

	if($type){
         $links->where('type=?',$type);
	}

	$total_links = $links->count();

        $pager = Pagination::factory(array(
            'total_items' => $total_links,
            'items_per_page' => 15,
            'view' => 'pager/common',
        ));

	 $view['type']=$type;
	 $view['is_logo']=$is_logo;
	 $view['pager']=$pager;
	 $view['links']=$links->offset($pager->offset)
                              ->limit($pager->items_per_page)
                              ->fetchArray();

	$this->_title('文字链接');
	$this->_render('_body', $view);

    }

    #添加或修改链接
    function action_add(){

	$id = Arr::get($_GET, 'id', 0);
        $link = Doctrine_Query::create()
                    ->from('Links')
                    ->where('id = ?', $id)
                    ->fetchOne();

        $view['link'] = $link;
        $view['err'] = '';

	if($_POST){

            // 链接内容数据
            $valid = Validate::setRules($_POST, 'links');
            $post = $valid->getData();
            if( ! $valid->check()){
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
                // 更新链接 or 创建链接
                if($link){
                    unset($post['id']);
                    $link->synchronizeWithArray($post);
                    $link->save();
                } else {
                    $link = new Links();
                    $link->fromArray($post);
                    $link->save();
                }

                if($_FILES['logo']['size'] > 0) {
                    // 上传的logo
                    $valid = Validate::factory($_FILES);
                    $valid->rules('logo', Model_Links::$up_rule);
                    if( ! $valid->check()) {
                        $view['err'] .= $valid->outputMsg($valid->errors('validate'));
                    } else {
                        // 处理logo图片
                        $path = DOCROOT.Model_Links::LOGO_PATH;
                        Upload::save($_FILES['logo'], $link->id, $path);

                        Image::factory($path.$link->id)
                                ->resize(140, 40, Image::NONE)
                                ->save($path.$link->id.'.jpg');

                        unlink($path.$link->id);
                    }
                }

                // 处理完毕后刷新页面
                $this->request->redirect('admin_links/index');
            }
        }
	
        $this->_title('添加新链接');
	$this->_render('_body',$view);
	
    }



    #删除链接
    function action_del(){
	$this->auto_render = FALSE;
	$cid=Arr::get($_GET, 'cid');
	$del = Doctrine_Query::create()
		->delete('Links')
                ->where('id =?',$cid)
	        ->execute();
    }
}

?>
