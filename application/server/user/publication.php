<?php

class Controller_User_Publication extends Layout_User
{
    function before()
    {
        parent::before();

        $topbar_links = array(
            'user_publication/index' => '我的投稿',
            'user_publication/form' => '新闻投稿',
        );
        View::set_global('topbar_links', $topbar_links);
    }

    function action_index()
    {
        $pub = Doctrine_Query::create()
		    ->select('id,title,user_id,is_read,create_at,update_at,reply')
                    ->from('PubContribute')
                    ->where('user_id = ?', $this->_user_id)
                    ->orderBy('create_at DESC');

        $total_pub = $pub->count();

        $pager = Pagination::factory(array(
            'total_items' => $total_pub,
            'items_per_page' => 12,
            'view' => 'pager/common',
        ));
        
        $view['pager'] = $pager;
        $view['pub'] = $pub->offset($pager->offset)
                             ->limit($pager->items_per_page)
                             ->fetchArray();

        $this->_title('我的投稿');
        $this->_render('_body', $view);
    }
    function action_view(){
	$pub_id = Arr::get($_GET, 'pub_id', 0);
        $view['pub'] = Doctrine_Query::create()
                    ->from('PubContribute')
                    ->where('id = ?', $pub_id)
		    ->addWhere('user_id=?',$this->_sess->get('id'))
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

	$this->_title('文章预览');
	$this->_render('_body',$view);
	
    }
    function action_form()
    {
	$pub_id = Arr::get($_GET, 'pub_id', 0);
        $pub = Doctrine_Query::create()
		    ->select('id,title,create_at,is_read')
                    ->from('PubContribute')
                    ->where('id = ?', $pub_id)
                    ->fetchOne();
	$view['err']='';
       $view['pub'] = $pub;

	if($_POST){

            if(Arr::get($_POST,'title')=='' OR Arr::get($_POST,'content')==''){
                $view['err'] = '标题或内容不能为空';
            } else {


                // 添加或修改内容
                if($pub){
                    unset($pub['id']);
		    $post['title']=  Arr::get($_POST, 'title');
		    $post['content']=  Arr::get($_POST, 'content');
                    $pub->synchronizeWithArray($post);
                    $pub->save();
                } else {
                    $pub = new PubContribute();
		    $post['title']=  Arr::get($_POST, 'title');
		    $post['content']=  Arr::get($_POST, 'content');
		    $post['user_id'] = $this->_sess->get('id');
		    $post['create_at'] = date('Y-m-d H:i:s');
		    $post['update_at'] = date('Y-m-d H:i:s');
                    $pub->fromArray($post);
                    $pub->save();
                }
                // 处理完毕后刷新页面
                $this->request->redirect('user_publication/index');
            }
        }
      
        $this->_title('新闻投稿');
        $this->_render('_body', $view);
    }
}