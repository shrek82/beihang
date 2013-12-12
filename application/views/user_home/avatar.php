<!-- user_home/avatar:_body -->
<script type="text/javascript" src="/static/js/jquery-pack.js"></script>
<script type="text/javascript" src="/static/js/jquery.imgareaselect.min.js"></script>

<?php if ($large_image_path): ?>
    <script type="text/javascript">
        function preview(img, selection) {
    	var scaleX = <?= $thumb_width; ?> / selection.width;
    	var scaleY = <?= $thumb_height; ?> / selection.height;

    	$('#thumbnail + div > img').css({
    	    width: Math.round(scaleX * <?php echo $current_large_image_width; ?>) + 'px',
    	    height: Math.round(scaleY * <?php echo $current_large_image_height; ?>) + 'px',
    	    marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
    	    marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
    	});
    	$('#x1').val(selection.x1);
    	$('#y1').val(selection.y1);
    	$('#x2').val(selection.x2);
    	$('#y2').val(selection.y2);
    	$('#w').val(selection.width);
    	$('#h').val(selection.height);
        }

        $(document).ready(function () {
    	$('#save_thumb').click(function() {
    	    var x1 = $('#x1').val();
    	    var y1 = $('#y1').val();
    	    var x2 = $('#x2').val();
    	    var y2 = $('#y2').val();
    	    var w = $('#w').val();
    	    var h = $('#h').val();
    	    if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
    		alert("请先选择上传图片");
    		return false;
    	    }else{
    		return true;
    	    }
    	});
        });

        $(window).load(function () {
    	$('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height / $thumb_width; ?>', onSelectChange: preview });
        });
    </script>

<?php endif; ?>


<?php if ($error): ?>
	<div class="notice"><?= $error ?></div>
<?php endif; ?>

<?php if ($large_image_path): ?>

	    <div id="big_right">
	        <div id="plugin_title">请拖动左侧图片选择裁切区域：</div>
	        <div id="user_avatar">
	    	<div align="center">
	    	    <img src="/<?= $large_image_path ?>?rand=<?=$rand?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />
	    	    <div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width; ?>px; height:<?php echo $thumb_height; ?>px;">
	    		<img src="/<?= $large_image_path ?>?rand=<?=$rand?>" style="position: relative;" alt="Thumbnail Preview" />
	    	    </div>
	    	    <br style="clear:both;"/>
	    	    <form name="thumbnail" action="<?=URL::site('/user_home/avatar') ?>" method="post"  id="thumbnailForm">
	    		<input type="hidden" name="x1" value="" id="x1" />
	    		<input type="hidden" name="y1" value="" id="y1" />
	    		<input type="hidden" name="x2" value="" id="x2" />
	    		<input type="hidden" name="y2" value="" id="y2" />
	    		<input type="hidden" name="w" value="" id="w" />
	    		<input type="hidden" name="h" value="" id="h" />
	    		<input type="hidden" name="large_image_path" value="<?= $large_image_path ?>"  />
	    		<br>
	    		<input type="submit" name="upload_thumbnail" value="确定裁剪" id="save_thumb" class="button_blue" onClick="return checkCut()"  />
	    		<input type="button"  value="重新上传" id="save_thumb" class="button_gray" onclick="window.location.href='<?= URL::site('user_home/avatar?action=del') ?>'" />
	    	    </form>
	    	</div>
	        </div>
	    </div>

<script type="text/javascript">
        function checkCut(){
            if($('#x1').val()=='' || $('#y1').val()==''){
                alert('您还没有裁剪区域，请拖动左侧图片指定区域，谢谢！');
                return false;
            }
        }
</script>

<?php else: ?>
		<div id="big_right">
    <?php if (isset($face48)): ?>
		        <div id="plugin_title">头像上传成功</div>
		        <div id="user_avatar">

			    <a href="<?=URL::site('user_home/avatar')?>">返回重新上传</a>

		        </div>
    <?php else: ?>

		        <div id="plugin_title">选择并上传照片</div>
		        <div id="user_avatar">

		    	<p style="color:green;font-weight: bold">提示：请选择jpg、png或gif格式的图片，且图片不大于1M</p>
		    	<form name="photo" enctype="multipart/form-data" action="<?=URL::site('/user_home/avatar') ?>" method="post">
		    	    <p><input type="file" name="image" /></p>
		    	    <p><input type="submit" name="upload" value="开始上传" class="button_blue" /></p>
		    	</form>
		        </div>

    <?php endif; ?>
		    </div>
<?php endif; ?>