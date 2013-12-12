<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?= @$_user_header ?>
<body>
<div id="append_parent"></div>
<!--top  -->
<?= @$_user_nav ?>
<!--//top -->

<!-- main -->
<div id="main">
<?= @$_user_left ?>
<?= @$_body ?>
<?= @$_user_right ?>
<div class="clear"></div>
</div>
<!-- //main -->

<!--footer -->
<?= @$_user_footer ?>
<!--//footer -->
</body>
</html>
