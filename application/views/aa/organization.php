<!-- aa/organization:_body -->
<!--main -->
<p><img src="/static/images/aa_title.gif"></p>

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

    <div>

	<div style="width:650px;float:left">

     <div style="text-align:right">
<?php if(count($all_info)>=2):?>
         <select name="redirect_page" id="redirect_page" style="color: #666" onchange="window.location='<?=
                          URL::site('aa/organization')?>?id='+this.value;">
      <?php foreach($all_info as $i):?>
      <option value="<?=$i['id']?>" <?php if($main['id']==$i['id']):?>selected<?php endif;?>><?=$i['title']?></option>
      <?php endforeach;?>
</select>
<?php endif;?>
  </div>

	    <div  id="intro" style="line-height:1.7em;color:#333">
<?= @$main['content']?$main['content']:'暂无内容';?></div>
	</div>
	<div style="width:200px;float:right;border:1px solid #E3EEF8;padding:10px;">
            <ul id="aa_info">
                <li><a href="<?= URL::site('aa') ?>" >北京航空航天大学校友总会简介</a></li>
                <li><a href="<?= URL::site('aa/constitution') ?>" >北京航空航天大学校友总会章程</a></li>
                <li><a href="<?=URL::site('aa/organization')?>" class="cur">北京航空航天大学校友总会理事名单</a></li>
    		          <li><a href="<?=URL::site('aa/memorabilia')?>" >北京航空航天大学校友总会大事记</a></li>
                </ul>

            </div>

    	<div class="clear"></div>
        </div>
    </div>
    <!--//校友总会 -->