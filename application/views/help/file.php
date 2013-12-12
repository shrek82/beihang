<!-- help/file:_body -->
<style type="text/css">
        #searchlist li{ float: left;width:200px;margin-bottom:10px}
        #file_table thead td{height:25px; padding:4px; color: #174679; font-size: 14px;border-bottom:1px solid #DFECF5; font-weight: bold}
        #file_table tbody td{border-bottom:1px solid #DFECF5;height:25px; padding:4px;}
        #file_table tbody tr:hover td{ background-color: #E3EDF7; border-bottom: 1px solid #BFD1DB}
</style>
<p><img src="/static/images/help_title.gif"></p>
<div style="margin:0px 20px;padding:4px 10px;">
        <h2>档案查询：</h2>
        <?php if ($_ROLE == '管理员' OR $_ROLE == '校友(已认证)'): ?>

                <form action="<?= URL::site('help/file') ?>" method="post" id="search_form"  name="search_form" onsubmit="return false;">
                        <table width="100%" border="0"  cellpadding="0" cellspacing="0" >
                                <tr>
                                        <td style="width:60px; text-align: right; padding:10px 5px">姓名：</td>
                                        <td><input type="text" id="realname" name="realname" value="" class="input_text" style="width:250px;" placeholder="完整姓名或关键字"></td>
                                </tr>
                                <tr>
                                        <td style="width:60px; text-align: right; padding:10px 5px">就读时间：</td>
                                        <td>		<select id="begin_year" name="begin_year" style="padding:1px 2px;">
                                                        <option value="" >不限 </option>
                                                        <?php for ($i = date('Y'); $i >= 1900; $i--): ?>
                                                                <option value="<?= $i ?>" ><?= $i ?></option>
                                                        <?php endfor ?>

                                                </select>&nbsp;至&nbsp;<select id="graduation_year" name="graduation_year" style="padding:1px 2px;">
                                                        <option value="" >不限 </option>
                                                        <?php for ($i = date('Y'); $i >= 1900; $i--): ?>
                                                                <option value="<?= $i ?>" ><?= $i ?></option>
                                                        <?php endfor ?>

                                                </select>年</td>
                                </tr>
                                <tr>
                                        <td style="width:60px; text-align: right; padding:10px 5px">专业：</td>
                                        <td><input type="text" id="speciality" name="speciality" value="" class="input_text" style="width:350px;" ><span style="color:#999">&nbsp;&nbsp;输入关键字，如：国际金融</span></td>
                                </tr>

                                <tr>
                                        <td style="width:60px; text-align: right; padding:10px 5px">学院、系：</td>
                                        <td><input type="text" id="institute" name="institute" value="" class="input_text" style="width:350px;" ><span style="color:#999">&nbsp;&nbsp;输入关键字，如：经济学院</span></td>
                                </tr>

                                <tr>
                                        <td style="width:60px; text-align: right; padding:20px 5px"></td>
                                        <td>	<input type="button" onclick="search()" value="立即查询" id="search_button" class="button_blue"  style="width:70px;padding: 0px 10px">
                                                <input type="button" onclick="window.history.back()" class="button_gray" value="返回">

                                        </td>
                                </tr>
                        </table>
                    <div style="color:#999"><br>温馨提示：如果查找后未找到相应结果，建议降低条件后重新查找。</div>
                </form>
        <?php else: ?>
                <span style="color:#999">对不起，只有管理员或已认证校友才能查询。</span>
        <?php endif; ?>
        <div id="search_result" style="margin:15px 0;">
        </div>
</div>

<script type="text/javascript">

        function search(){
                var realname = document.getElementById('realname').value;
                var begin_year = document.search_form.begin_year.options[document.search_form.begin_year.selectedIndex].value;
                var graduation_year = document.search_form.graduation_year.options[document.search_form.graduation_year.selectedIndex].value;
                var speciality = document.getElementById('speciality').value;
                var institute = document.getElementById('institute').value;
                var url = '<?= URL::site('help/file') ?>';
                var req = new Request({
                        url: url,
                        data: 'realname='+realname+'&begin_year='+begin_year+'&graduation_year='+graduation_year+'&speciality='+speciality+'&institute='+institute,
                        type: 'post',
                        beforeSend: function(){
                                $('#search_button').attr('value','请稍后');
                                $('#search_result').html('<img src="<?= URL::base() . 'candybox/Media/image/loading.gif' ?>" width="18" />');
                        },
                        success: function(html){
                                $('#search_button').attr('value','查询');
                                $('#search_result').html(html);
                        }
                });
                req.send();
        }

        function detail(id){
                var box = new Facebox({
                        url: '<?= URL::site('help/fileView?id=') ?>'+id,
                        width:600,
                        height:300
                });
                box.show();
        }
</script>