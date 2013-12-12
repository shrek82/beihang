<!--main -->
<script type="text/javascript">
    function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
	    var menu=document.getElementById(name+i);
	    var con=document.getElementById("con_"+name+"_"+i);
	    menu.className=i==cursel?"cur":"";
	    con.style.display=i==cursel?"block":"none";
	}
    }
</script>
<p><img src="/static/images/aa_title.gif"></p>

<div class="blue_tab" style="margin: 15px 20px">
    <ul>
	<li><a href="<?=URL::site('aa')?>" id="one1"  class="cur">校友总会</a></li>
	<li><a href="<?=URL::site('aa/branch')?>" id="one2"   >地方校友会</a></li>
	<li><a href="<?=URL::site('aa/institute')?>" id="one3" >院系分会</a></li>
	<li><a href="<?=URL::site('aa/club')?>" id="one4" >俱乐部</a></li>
    </ul>
</div>
<!--校友总会 -->
<div id="con_one_1" class="tab_content" >

    <div>

	<div style="width:650px;float:left">

	    <div  id="intro" style="line-height:1.7em;color:#333">
<?= $main['intro'] ?></div>
	</div>
	<div style="width:200px;float:right;border:1px solid #E3EEF8;padding:10px;">
            <ul id="aa_info">
                <li><a href="<?= URL::site('aa') ?>" class="cur">北京航空航天大学校友总会简介</a></li>
                <li ><a href="<?= URL::site('aa/constitution') ?>" <?php if($_C=='constitution'):?>class="cur"<?php endif;?>>北京航空航天大学校友总会章程</a></li>
                <li><a href="<?=URL::site('aa/organization')?>" <?php if($_C=='organization'):?>class="cur"<?php endif;?>>北京航空航天大学校友总会理事名单</a></li>
    		          <li><a href="<?=URL::site('aa/memorabilia')?>" <?php if($_C=='memorabilia'):?>class="cur"<?php endif;?>>北京航空航天大学校友总会大事记</a></li>
<?php foreach ($other_info as $inf): ?>
<li><a href="<?= URL::site('aa/other?id='.$inf['id']);?>" ><?= $inf['title'] ?></a></li>
<?php endforeach; ?>
            </ul>

            </div>

    	<div class="clear"></div>
        </div>
        <script type="text/javascript">
    	candyImageAutoResize('intro');
    	function getInfo(aa,id){
    	    $('intro').load('<?= URL::site('aa/info') ?>?aa='+aa+'&id='+id);
    	}
    	function memorabilia(){
    	    $('intro').load('<?= URL::site('aa/memorabilia') ?>');
    	}

    	$$('#aa_info a').addEvent('click', function(){
    	    $$('#aa_info a').removeClass('cur');
    	    this.addClass('cur');
    	});

    	function displaymem(mid){
    	    var obj=document.getElementById('mem_'+mid);
    	    if(obj.style.display=='none'){
    		obj.style.display='';
    	    }
    	    else{
    		obj.style.display='none';
    	    }
    	}
        </script>
    </div>
    <!--//校友总会 -->