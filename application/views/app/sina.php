<!-- app/sina:_body -->
<div style>
<?php if($binding):?>
<h2 style="color:#0A890A">您已经绑定新浪微博帐号了，不需要再次绑定了，谢谢您的配合。</h2>
<?php else:?>
<table width="width:95%; margin:20px auto">
        <tr>
                <td><span class="middle"><img src="/static/logo/sina/24x24.png" /></span> 新浪微博 </td>
                <td><a href="<?=$bindingUrl?>" ></a></td>
        </tr>
</table>
<?php endif; ?>
</div>


