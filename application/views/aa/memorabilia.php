<p><img src="/static/images/aa_title.gif"></p>
<script language="javascript">
function displaymem(id){
    var obj=document.getElementById('mem_'+id);
    if(obj.style.display=='none'){
	obj.style.display='block';
    }
    else{
	obj.style.display="none";
    }
}
</script>
<div class="blue_tab" style="margin: 15px 20px">
    <ul>
	<li><a href="<?=URL::site('aa')?>" id="one1"  class="cur">校友总会</a></li>
	<li><a href="<?=URL::site('aa/branch')?>" id="one2"   >地方校友会</a></li>
	<li><a href="<?=URL::site('aa/institute')?>" id="one3" >院系分会</a></li>
        <li><a href="<?=URL::site('aa/industry')?>"  >行业分会</a></li>
	<li><a href="<?=URL::site('aa/club')?>" id="one4" >俱乐部</a></li>
    </ul>
</div>
<!--校友总会 -->
<div id="con_one_1" class="tab_content" >

    <div style="width:650px;float:left">
<h3 id="title_name">北京航空航天大学校友总会大事记</h3>
<div id="memorabilia">
    <table>
<?php foreach($memorabilia AS $m):?>
	<tr>
	    <td style="width:550px"><a href="javascript:displaymem(<?=$m['id']?>)"><?= Text::limit_chars($m['title'], 40, '...') ?></a></td>
	    <td style="color:#666"><?= date('Y-n-d', strtotime($m['create_at'])); ?></td>
	</tr>
	<tr id="mem_<?=$m['id']?>" style="display:none">
	    <td colspan="2"  class="mem_con" style="background:#FAFAED">
<?=$m['content']?>
	    </td>
	</tr>

<?php endforeach;?>
	</table>
</div>
</div>

<div style="width:200px;float:right;border:1px solid #E3EEF8;padding:10px;">
<ul id="aa_info">
<li><a href="<?= URL::site('aa') ?>" >北京航空航天大学校友总会简介</a></li>
<li><a href="<?= URL::site('aa/constitution') ?>" >北京航空航天大学校友总会章程</a></li>
<li><a href="<?=URL::site('aa/organization')?>" >北京航空航天大学校友总会理事名单</a></li>
<li><a href="<?=URL::site('aa/memorabilia')?>" class="cur">北京航空航天大学校友总会大事记</a></li>
<?php foreach ($other_info as $inf): ?>
<li><a href="<?= URL::site('aa/other?id='.$inf['id']);?>" ><?= $inf['title'] ?></a></li>
<?php endforeach; ?>

 </ul>
</div>


 </div>