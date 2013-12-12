<!-- classroom_home/addressbook:_body -->
<style type="text/css">
.abook{ margin:10px 0 0 0; padding:10px; border: 1px solid #f6f6f6;  width: 350px; height:90px;float: left; margin-right: 10px}
.abook:hover{ border: 1px solid #ddd; background: #f5f5f5; }
.abook .abook_left{ float: left; text-align: center; width: 60px}
.abook .abook_right{ float: left;width:210px; height: 100px; color: #666; margin-left:10px;}
</style>
<div id="admin950">

    <table cellspan="0" border="0">
	<tr>
	    <td><img src="/static/images/page_excel.gif" /></td>
	    <td><a href="<?=URL::site('classroom_home/export?id='.$_CLASSROOM['id'])?>" target="_blank">下载班级通讯录</a></td>
	</tr>
    </table>

    <?php foreach($addressbook as $ab): ?>
    <div class="abook">
	<div class="abook_left">
                <a href="<?= URL::site('user_home?id='.$ab['id']) ?>"><?= View::factory('inc/user/avatar', array('id' => $ab['id'], 'size'=>48,'sex'=>$ab['sex'])) ?>
                <?= $ab['realname'] ?></a>
         </div>
            <div class="abook_right">
手机: <?= $ab['Contact']['mobile']?$ab['Contact']['mobile']:'-';?></br>
电话: <?= $ab['Contact']['tel']?$ab['Contact']['tel']:'-' ?></br>
QQ: <?= $ab['Contact']['qq']?$ab['Contact']['qq']:'-' ?></br>
邮箱: <?= $ab['account']?$ab['account']:'-';?></br>
地址: <?= $ab['Contact']['address']?$ab['Contact']['address']:'-'; ?>

            </div>

	</div>

    <?php endforeach; ?>

<div class="clear"></div>
    <?= $pager ?>
</div>
