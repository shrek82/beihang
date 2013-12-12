<form action="<?= URL::query(); ?>" id="news_category_form" method="post">
    <div><label>名称</label>(不可重复)<br />
        <input size="30" type="text" name="name" value="<?= $category['name'] ?>" class="input_text"/>
        <select name="order_num">
            <option value="<?= $total ?>">排序</option>
            <?php for($i=1; $i<=$total+1; $i++): ?>
            <option value="<?= $i ?>" <?= $category['order_num'] == $i ? 'selected':'' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>
        <input type="hidden" name="is_public" value="0" />
        <input type="checkbox" name="is_public" value="1" <?= $category['is_public'] ? 'checked':'' ?> /> 可见
    </div>
    <div><label>介绍</label><br />
        <textarea style="width:600px; height:60px" name="intro"  class="input_text"><?= $category['intro'] ?></textarea>
    </div>
    <div>
        <input type="hidden" name="id" value="<?= $category['id'] ?>" />
        <input type="hidden" name="aa_id" value="0" />
        <input type="button" id="submit_button" value="<?= $btn ?>" onclick="category_send()"  class="button_blue" />
    </div>
</form>