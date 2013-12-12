<!-- aa_admin_news/category:_body -->
<div id="admin950">
    <?php if (count($category) == 0): ?>
        <span class="nodata">没有任何分类信息。</span>
    <?php else: ?>
        <table id="bench_list" width="100%"  border="0"  cellpadding="0" cellspacing="0">
            <tr>
                <th width="5%" style="text-align:center">分类排序</th>
                <th width="50%" style="text-align:left">分类名称</th>
                <th width="7%" style="text-align:center">新闻总数</th>
                <th width="7%"  style="text-align:center">修改</th>
                <th width="7%"  style="text-align:center">删除</th>
            </tr>
            <?php if (count($category) > 0): ?>
                <?php foreach ($category as $key => $c): ?>
                    <tr id="c_<?= $c['id'] ?>" class="<?=(($key+1)%2)==0?'even_tr':'odd_tr';?>">
                        <td style="text-align:center"><?= $c['order_num'] ?></td>
                        <td height="25" style="padding:0px 10px" >
                        <?= $c['name'] ?>
                        </td>
                        <td style="text-align:center"><?= $c['news_count'] ?></td>
                        <td style="text-align:center"><a href="/aa_admin_news/category?id=<?=$_ID?>&cid=<?=$c['id']?>">编辑</a></td>
                        <td style="text-align:center"><a href="javascript:del(<?= $c['id'] ?>)">删除</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td height="25" style="padding:0px 10px" colspan="4" >
                        <span class="nodata">没有任何分类信息。</span>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>


    <br><Br><br>
    <form method="post" action="<?= URL::query(); ?>" id="category_form">
        <div><label>分类名称</label>(不可重复)<br />
            <input size="50" type="text" name="name" value="<?= @$cur_cat['name'] ?>" class="input_text"  />
            <input type="hidden" name="is_public" value="0" />
            <input type="checkbox" name="is_public" value="1" <?= (isset($cur_cat['is_public']) && $cur_cat['is_public'] == false) ? '' : 'checked' ?> /> 可见
        </div>
        <div><label>分类排序</label>(越小越靠前)<br />
            <input size="50" type="text" name="order_num" value="<?= @$cur_cat['order_num'] ?>" class="input_text"  />
        </div>
        <div><label>分类介绍</label><br />
            <textarea style="width:500px;height:100px" name="intro"><?= @$cur_cat['intro'] ?></textarea>
        </div>

        <div style="margin:10px 0">
            <input type="hidden" name="categoryid" value="<?= $cid ?>" />
            <input id="submit_button" onclick="category()" type="button" value="<?= $btn ?>"  class="button_blue"/>
            <?php if (@$cur_cat): ?>
                <a href="<?= URL::query(array('cid' => null)); ?>">取消修改</a>
            <?php endif; ?>
        </div>
    </form>
</div>
<script type="text/javascript">
    function category(){
        var form=new ajaxForm('category_form',{submitButton:'submit_button', redirect:'<?= $_URL ?>' });
        form.send();
    }

    function del(cid){
        var b = new Facebox({
            title: '删除确认！',
            icon:'question',
            message: '确定要删除此分类吗？<br>注意删除分类将同时删除分类下所有新闻内容！',
            ok: function(){
                new Request({
                    url: '<?= URL::site('aa_admin_news/delcategory?cid=') ?>'+cid+'&id='+<?=$_ID?>,
                    type: 'post',
                    success: function(data){
                        candyDel('c_'+cid);
                    }
                }).send();
                b.close();
            }
        });
        b.show();
    }
</script>