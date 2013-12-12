<?php if(count($users) == 0): ?>
<p class="nodata">很抱歉，没有找到您要找到的校友。</p>
<?php else: ?>
<p class="search_count">
    与条件“<?= $name ?>
    <select name="city" onchange="search('user', 1)" style="display:none">
        <option value="">所有城市</option>
        <?php foreach($cities as $c): ?>
        <option value="<?= $c ?>" <?= $c==$city?'selected':''?>><?= $c ?></option>
        <?php endforeach; ?>
    </select>
    ”相符的校友共有<?= $pager->total_items ?>人
</p>



<?php foreach($users as $u): ?>
<div class="user_box" >
    <a href="<?= URL::site('user_home?id='.$u['id']) ?>">
    <?= View::factory('inc/user/avatar', array('id' => $u['id'], 'size'=>48,'sex'=>$u['sex'])) ?>
    <?= $u['realname'] ?></a><br>
    <span style="color:#999"><?=$u['city']?Text::limit_chars($u['city'],4, '...'):'-'; ?></span>
</div>
<?php endforeach; ?>
<?= $pager ?>
<?php endif; ?>