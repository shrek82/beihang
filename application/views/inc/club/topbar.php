<?php
    $club_links = array(
        'club_home/index' => '首页',
        'club_home/bbs' => '论坛',
        'club_home/event' => '活动',
        'club_home/album' => '相册',
        'club_home/member' => '成员('.$_CLUB_MNUM.')',
        'club_home/point' => '积分('.$_CLUB_POINT.')'
    );

    if($_MEMBER && $_MEMBER['manager'] == TRUE){
        $club_links['club_admin/index'] = '管理';
    }
?>
<div>

    <?php if($_ID):?>
    <a style="color:#666" href="<?= URL::site('aa_home/club?id='.$_ID) ?>"> <?= $_CLUB['Aa']['name'] ?></a>
      <?php else:?>
     <a style="color:#666" href="<?= URL::site('aa') ?>">校友总会 &raquo;</a>
<?php endif;?>
</div>
<div id="aa_top">
    <div class="left">

	    <div style="float: left;width:100px"><img src="<?= Model_Club::logo($_ID) ?>" /></div>
	    <div style="float:left;width:500px">
		<div><span style="font-size:22px; color: #333;font-family:'Microsoft YaHei','Microsoft JhengHei'"><?= $club['name'] ?></span></div>
		<div id="aa_home_nav">
		<?php foreach($club_links as $uri=>$name): ?>
                <a href="<?= URL::site($uri.'?id='.$_ID.'&club_id='.$_ID) ?>" <?= $_C.'/'.$_A == $uri ? 'class="cur"':'' ?>><?= $name ?></a>
                <?php endforeach; ?>
		</div>
	    </div>
    </div>
    
    <div class="right">
                <?php if($_MEMBER && ! $_MEMBER['chairman']): ?>
                <img src="<?= URL::base().'static/images/door.gif' ?>" />
                <a id="leave_club" href="javascript:leave_club()">退出俱乐部</a>
                <?php endif; ?>

                <?php if($_UID && !$_MEMBER): ?>
                <img src="<?= URL::base().'static/images/+.gif' ?>" id="joinImage"/>
                <a id="join_club" href="javascript:join_club()">加入俱乐部</a>
                <?php endif; ?>
    </div>
    <div class="clear"></div>
</div>

<script type="text/javascript">
function applyjoin_club(){

    var box = new Facebox({
        title: '申请加入俱乐部',
        url: '<?= URL::site('club_home/join').URL::query() ?>',
        ok: function(){
            new ajaxForm('apply_form', {callback:function(){
                box.close();
            }}).send();
        }
    });
    box.show();
}

function join_club(){
    document.getElementById('joinImage').src='/static/images/user/loading6.gif';
	var join=new Request({
	    url: '<?= URL::site('club_home/join').URL::query() ?>',
	    type: 'post',
	    success: function(){
		location.reload();
	    }
	});
	join.send();
}

function leave_club(){
    var box = new Facebox({
        title: '确定要退出本俱乐部？',
        message: '这个操作将会抹除你在本俱乐部的积分，确认要退出吗？',
        submitValue: '确认离开',
        ok: function(){
            $('leave_club').load('<?= URL::site('club_home/leave').URL::query() ?>');
            history.go(0)
        }
    });

    box.show();
}
</script>