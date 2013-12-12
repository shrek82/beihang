<?php
/**
  +-----------------------------------------------------------------
 * 名称：引用评论视图
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-11 下午3:13
  +-----------------------------------------------------------------
 */
if ($ids) {
    $ids = str_replace(',,', ',', $ids);
    $cmt_ids = explode(',', $ids);
    $total_quote=count($cmt_ids);
    if ($total_quote > 0) {
        $sql='SELECT c.id,c.content,c.post_at,c.user_id,u.realname AS realname,u.sex AS sex FROM `comment` AS `c` JOIN `user` AS `u` ON (`u`.`id` = `c`.`user_id`) WHERE `c`.`id` in ('.implode(",",$cmt_ids).')  ORDER BY `c`.`id` ASC';
        $comment = DB::query(Database::SELECT,$sql)->execute()->as_array();
    }
}

if(isset($comment) AND count($comment)>0){
    $quote_str='';
    foreach($comment AS $key=>$c){
        $louceng=isset($floor['floor_'.$c['id']])?$floor['floor_'.$c['id']]:'null';
        $content = Text::limit_chars(strip_tags($c['content']),200, '...');
        //$content=Emotion::autoToUrl($content);
        $quote_str='<div class="quote_new">'.$quote_str.'<div class="quote_author"><div class="quote_name"> <span style="color:#f60"><strong>'.$louceng.'</strong> 楼</span>&nbsp;&nbsp;'.$c['realname'].'&nbsp;&nbsp;发布于 '.$c['post_at'].'</div><div class="quote_floor">'.($key+1).'</div></div><div class="quote_content">'.$content.'</div></div>';
    }
    echo $quote_str.'<div style="height:10px">&nbsp;</div>';
}
?>



