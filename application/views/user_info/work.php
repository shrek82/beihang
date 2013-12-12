<script type="text/javascript" src="/static/My97DatePicker/WdatePicker.js"></script>

<div id="big_right">
        <div id="plugin_title">编辑资料</div>

        <div class="tab_gray" id="user_topbar" style="margin-top:10px">
                <ul>
                        <li><a href="<?= URL::site('user_info/base') ?>" style="width:50px">基本信息</a></li>
                        <li><a href="<?= URL::site('user_info/work') ?>" class="cur" style="width:50px">工作信息</a></li>
                        <li><a href="<?= URL::site('user_info/account') ?>" style="width:50px">账号设置</a></li>

       <?php if(isset($_CONFIG) AND $_CONFIG->modules['binding']):?>
       <li><a href="<?= URL::site('user_info/binding') ?>" style="width:50px">微博绑定</a></li>
       <?php endif;?>

                </ul>
        </div>

        <div id="user_info">
                <?php echo Candy::import('datepicker'); ?>
                <?php if (count($works) == 0): ?>
                        <p class="ico_info icon-i">还没有记录任何工作信息</p>
                <?php else: ?>



                        <table width="100%" id="bbs_table">
                                <thead>
                                        <tr >
                                                <td style="width:200px">工作时间</td>
                                                <td style=" text-align: left" >公司及职位</td>
                                                <td style=" text-align: center">操作</td>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php if (!$works): ?>
                                                <tr >
                                                        <td colspan="3" style="border:0"><div class="nodata">你暂时还没有填写任何工作信息！</div></td>
                                                </tr>
                                        <?php endif; ?>
                                        <?php foreach ($works as $w): ?>
                                                <tr style="border-bottom: 1px dotted #eee" onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='' id="work_<?= $w['id'] ?>">
                                                        <td ><?= $w['start_at'] ?> -
                                                                <?= $w['leave_at'] == '0000-00-00' ? '至今' : $w['leave_at'] ?></td>
                                                        <td ><?= $w['company'] ?>(<?= $w['job'] ?>)</td>
                                                        <td class="date"><a href="/user_info/work?wid=<?=$w['id']?>">修改</a> | <a href="javascript:del(<?= $w['id'] ?>)">删除</a></td>
                                                </tr>
                                        <?php endforeach; ?>
                                </tbody>
                        </table>

                <?php endif; ?>
                <form action="<?= $_URL ?>" id="user_work" method="post" style="margin-top:20px">
                        <div><label>公司(单位)名称</label><br />
                                <input type="text" name="company" size="60" class="input_text" value="<?=@$work['company']?>"/>
                        </div>
                        <div><label>所属行业</label><br />
                                <select name="industry" id="industry">
                                        <option value="">选择</option>
                                        <?php foreach ($industry AS $i): ?>
                                                <option value="<?= $i ?>" <?=@$work['industry']==$i?'selected':'';?>><?= $i ?></option>
                                        <?php endforeach; ?>
                                </select>
                        </div>
                        <div><label>职位</label><br />
                                <input type="text" name="job" size="60" class="input_text" value="<?=@$work['job']?>"/>
                        </div>
                        <div><label>任职时间</label><br />
                                <input type="text" name="start_at" class="input_text"  value="<?=@$work['start_at']?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"/> -
                                <input type="text" name="leave_at" class="input_text"  value="<?=isset($work['leave_at'])?str_replace('0000-00-00','',$work['leave_at']):'';?>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
                                (不填则为至今在职)
                        </div>
                        <div>
                                <input type="button" id="submit_button" onclick="save()" value="<?=@$work?'修改':'添加'?>" class="input_submit" />
                                <input type="button"  value="取消" class="input_submit gray"  onclick="window.history.back()"/>
                        </div>
                </form>
        </div>
</div>

<script type="text/javascript">
    function save(){
        new ajaxForm('user_work',{textSending: '发送中',textError: '重试',textSuccess: '<?=@$work?'修改':'添加'?>成功',callback:function(id){
                window.location.href='/user_info/work/';
            }}).send();
    }

        function del(cid){
                var b = new Facebox({
                        title: '删除确认！',
                        message: '确定要删除此工作信息吗？注意删除后不可再恢复！',
                        icon:'question',
                        ok: function(){
                                new Request({
                                        url: '<?= URL::site('user_info/delWork?cid=') ?>'+cid,
                                        type: 'post',
                                        success: function(){
                                                candyDel('work_'+cid);
                                        }
                                }).send();
                                b.close();
                        }
                });
                b.show();
        }
</script>