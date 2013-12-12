<style type="text/css">
    #expdiv li{ float: left;margin: 1px; }
    #expdiv li a:link,#expdiv li a:visited{display: block; width: 25px; height: 25px; text-align: center; line-height: 25px;border: 1px solid #EDEDED}
    #expdiv li a:hover{border: 1px solid #FAF7E8; border: 1px solid #43B71C}
</style>
<?php
$exparray =Kohana::config('expression.default');
?>
<div id="expdiv">
    <ul style="padding: 0; margin: 0">
	<?php foreach ($exparray AS $name => $path): ?>
    	<li style="float:left;padding:2px"><a href="javascript:;" onclick="insertstr('[<?= $name ?>]')" title="<?= $name ?>"><img src="/static/homepage/expression/<?= $path ?>"></a></li>
	<?php endforeach; ?>
    </ul>
</div>

