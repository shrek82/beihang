<!-- admin/index:_body -->
<style type="text/css">
    table th{ border-top-width: 0}
    table td{padding:5px}
</style>
<table class="admin_table" border="0" width="98%" class="admin_talbe">
    <thead>
	<tr>
	    <th colspan="8"><b>新闻统计</b></th>
	</tr>
    </thead>
    <tbody>
	<tr>
	    <td>新闻总数：<a href="<?=URL::site('admin_news/index')?>" title="点击浏览" ><?=$news_count['all']?>条</a></td>
	    <td></td>
	    <td>已审核：<a href="<?=URL::site('admin_news/index?release=1')?>" title="点击浏览" style="color:#216F05"><?=$news_count['isRelease']?>条</a></td>
	    <td></td>
	    <td>未审核：<a href="<?=URL::site('admin_news/index?release=0')?>" title="点击浏览" style="color:#c00"><?=$news_count['notRelease']?>条</a></td>
	    <td></td>
	    <td>今日发布：<a href="<?=URL::site('admin_news/index?today=1')?>" title="点击浏览" style="color:#0A36A7"><?=$news_count['today']?>条</a></td>
	    <td></td>
	</tr>
    </tbody>

    <thead>
	<tr>
	    <th colspan="8"><b>注册用户</b></th>
	</tr>
    </thead>
    <tbody>
	<tr>
	    <td>所有用户：<a href="<?=URL::site('admin_user/index')?>" title="点击浏览" ><?=$user_count['all']?>人</td>
	    <td></td>
	    <td>已激活：<a href="<?=URL::site('admin_user/index?role=校友')?>" title="点击浏览" style="color:#216F05"><?=$user_count['activated']?>人</a></td>
	    <td></td>
	    <td>未激活：<a href="<?=URL::site('admin_user/index?role=校友(未激活)')?>" title="点击浏览" style="color:#c00"><?=$user_count['notActive']?>人</a></td>
	    <td></td>
	    <td>今日注册：<a href="<?=URL::site('admin_user/index?role=1')?>" title="点击浏览" style="color:#0A36A7"><?=$user_count['today']?>人</a></td>
	    <td></td>
	</tr>
    </tbody>

    <thead>
    </tbody>
</table>

