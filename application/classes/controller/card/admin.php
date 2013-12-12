<?php
class Controller_Card_Admin extends Layout_Main{

    public $template = 'layout/admin';
    public $content_category=3;
    
    public function before() {
	parent::before();

	$card_admin= $this->_sess->get('card_admin');
	 if( ! $card_admin){
            $this->request->redirect('card/login');
        }
        $leftbar_links = array(
                'card_admin/index' => '内容管理',
                'card_admin/form' => '添加内容',
                'card_admin/user' => '申请用户',
	        'card_admin/logout' => '退出登陆',
        );

	$role = Session::instance()->get('card');
	View::set_global('type',$this->content_category);
        $this->_render('_body_top', null, 'card_admin/topbar');
        $this->_render('_body_left', compact('leftbar_links'), 'admin/leftbar');
    }

    //内容列表
    function action_index(){
		$type = Arr::get($_GET, 'type');
	$content = Doctrine_Query::create()
			->select('c.id,c.title,c.create_at,c.is_system,c.update_at')
			->from('Content c')
			->where('c.type=?',$this->content_category)
			->orderBy('c.order_num,c.id');

	$total_content = $content->count();

	$pager = Pagination::factory(array(
		    'total_items' => $total_content,
		    'items_per_page' => 15,
		    'view' => 'pager/common',
		));

	$view['pager'] = $pager;
	$view['content'] = $content->offset($pager->offset)
			->limit($pager->items_per_page)
			->fetchArray();

	$this->_title('北航龙卡内容管理');
	$this->_render('_body', $view);
    }


    //添加或修改内容
    function action_form() {
	$id = Arr::get($_GET, 'id', 0);
	$type=  Arr::get($_POST,'type');
	$view['err']='';
	$content = Doctrine_Query::create()
			->from('Content')
			->where('id = ?', $id)
			->fetchOne();
	$view['content'] = $content;
	if($_POST){
            $valid = Validate::setRules($_POST, 'content');
            $post = $valid->getData();
            if( ! $valid->check()){
                $view['err'] .= $valid->outputMsg($valid->errors('validate'));
            } else {
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
		    $post['create_at'] = date('Y-m-d H:i:s');
		    $post['update_at'] = date('Y-m-d H:i:s');
                    $content->fromArray($post);
                    $content->save();
                }

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
                        Upload::save($_FILES['file'], $content->id, $path);

                        Image::factory($path.$content->id)
                                ->resize(null,null, Image::NONE)
                                ->save($path.$content->id.'.jpg');

                        unlink($path.$content->id);
                    }
                }

                // 处理完毕后刷新页面
                $this->request->redirect('card_admin');
            }
        }
	
	    $count= Doctrine_Query::create()
	    ->from('Content')
            ->where('type=?',Model_Content::ZU_CARD_ID)
	    ->count();

	    $view['count']=$count+1;

	    $this->_render('_body',$view);
    }

    function action_user(){

	$type = Arr::get($_GET, 'type');
	$user = Doctrine_Query::create()
			->select('c.*')
			->from('CardApply c')
			->orderBy('c.id DESC');

	$total_user = $user->count();

	$pager = Pagination::factory(array(
		    'total_items' => $total_user,
		    'items_per_page' => 15,
		    'view' => 'pager/common',
		));

	$view['pager'] = $pager;
	$view['user'] = $user->offset($pager->offset)
			->limit($pager->items_per_page)
			->fetchArray();

	$this->_title('龙卡申请用户');
	$this->_render('_body', $view);
    }

    #删除内容
    function action_del(){
	$this->auto_render = FALSE;
	$cid=Arr::get($_GET, 'cid');
	$del = Doctrine_Query::create()
		->delete('Content')
                ->where('id =?',$cid)
		->addWhere('type=?',$this->content_category)
	        ->execute();

	$uploadFile = Model_Content::FILE_PATH . $cid . '.jpg';
	if (file_exists(DOCROOT . $uploadFile)){
	    echo(DOCROOT.$uploadFile);
         }
    }

    #删除内容
    function action_applydel(){
	$this->auto_render = FALSE;
	$cid=Arr::get($_GET, 'cid');
	$del = Doctrine_Query::create()
		->delete('CardApply')
                ->where('id =?',$cid)
	        ->execute();
    }


    //logout
    function action_logout(){
	$this->_sess->set('card_admin', null);
	$this->_redirect('card');
    }
}

?>
