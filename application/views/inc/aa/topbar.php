<?php
$aa_links = array(
    'aa_home/info' => '相关信息',
    'aa_home/news' => '新闻',
    'aa_home/event' => '活动',
    'aa_home/album' => '相册',
    'aa_home/club' => '俱乐部',
    'aa_home/member' => '成员',
);
?>
<div id="aa_top">
    <div class="left">
	<div ><span style="font-size:22px; color: #333;font-family:'Microsoft YaHei','Microsoft JhengHei'"><?= $aa['name'] ?></span>
	<span style="color:#999;padding:0 10px"><?= $aa['mcount']?'已有'.$aa['mcount'].'人':'暂时还没有人加入';?></span>
	</div>

	<div id="aa_home_nav">
  <a href="/aa_home?id=<?=$_AA['id'] ?>" <?= $_C.'/'.$_A == 'aa_home/index' ? 'class="cur"':'' ?>>首页</a>
		<?php foreach($aa_links as $uri=>$name): ?>
		<a href="<?= URL::site($uri.'?id='.$aa['id']) ?>" <?= $_C.'/'.$_A == $uri ? 'class="cur"':'' ?>><?= $name ?></a>
		<?php endforeach; ?>
		<a href="<?= URL::site('bbs/list?aid='.$aa['id']) ?>" >论坛</a>
		<?php if(($_MEMBER && $_MEMBER['permissions'])): ?>
		<a href="<?= URL::site('aa_admin/index?id='.$aa['id']) ?>" >管理</a>
		<?php endif;?>
		<div class="clear"></div>
	</div>

    </div>
    <div class="right">
                <?php if($_MEMBER && ! $_MEMBER['chairman']): ?>
	<img src="<?= URL::base().'static/images/door.gif' ?>" />
                <a id="leave_aa" href="javascript:leave_aa()">退会</a>
                <?php elseif($_MEMBER['chairman']):?>
                <span style="color:#999">您是创始人，不能退出</span>
                <?php endif; ?>

                <?php if($_UID && !$_MEMBER): ?>
                <img src="<?= URL::base().'static/images/+.gif' ?>" />
                <a id="join_aa" href="javascript:join_aa()">申请加入</a>
                <?php endif; ?>
                <img src="<?= URL::base().'static/ico/03060811.gif' ?>" />
                <a href="javascript:AddFavorite('http://zuaa.zju.edu.cn/aa_home?id=<?=$aa['id']?>','<?= $aa['name'] ?>')">加入收藏</a>
                <?php if($_AA['weibo']):?><p style="padding:5px"><a href="<?=$_AA['weibo']?>"><img src="/static/ico/sina_weibo.gif" border="0"></a></p> <?php endif;?>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">

function join_aa(){

    var box = new Facebox({
        title: '申请加入校友会',
        url: '<?= URL::site('aa_home/join').URL::query() ?>',
        ok: function(){
            new ajaxForm('apply_form', {callback:function(){
                box.close();
            }}).send();
        }
    });

    box.show();
}

function leave_aa(){
    var box = new Facebox({
        title: '确定要退出本校友会？',
        message: '这个操作将会抹除你在本校友会的积分，确认要退出吗？',
        submitValue: '确认离开',
        ok: function(){
            $('leave_aa').load('<?= URL::site('aa_home/leave').URL::query() ?>');
            history.go(0)
        }
    });

    box.show();
}
</script>