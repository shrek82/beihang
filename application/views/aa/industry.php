<!--main -->
<script type="text/javascript">
    function setTab(name, cursel, n) {
        for (i = 1; i <= n; i++) {
            var menu = document.getElementById(name + i);
            var con = document.getElementById("con_" + name + "_" + i);
            menu.className = i == cursel ? "cur" : "";
            con.style.display = i == cursel ? "block" : "none";
        }
    }
</script>
<script type="text/javascript" src="/static/js/title_style.js"></script>
<p><img src="/static/images/aa_title.gif"></p>

<div class="blue_tab" style="margin: 15px 20px">
    <ul>
        <li><a href="<?= URL::site('aa') ?>" >校友总会</a></li>
        <li><a href="<?= URL::site('aa/branch') ?>"  >地方校友会</a></li>
        <li><a href="<?= URL::site('aa/institute') ?>"  >院系分会</a></li>
        <li><a href="<?= URL::site('aa/industry') ?>"  class="cur">行业分会</a></li>
        <li><a href="<?= URL::site('aa/club') ?>" >俱乐部</a></li>
    </ul>
</div>


<!--学院分会 -->
<div id="con_one_3" class="tab_content" style="height: 450px">
    <?php if (!$institution): ?>
        <p class="nodata">很抱歉，行业分会还在整理当中。</p>
    <? else: ?>
        <ul class="aa_institution">
            <?php foreach ($institution as $i): ?>
                <li><a href="<?= URL::site('aa_home?id=' . $i['id']); ?>"  onmouseover="wsug(event, '<?= $i['name'] ?><br>联系人：<?= $i['contacts'] ? $i['contacts'] : '-'; ?><br>电话：<?= $i['tel'] ? $i['tel'] : '-'; ?><br>邮件：<?= $i['email'] ? $i['email'] : '-'; ?><br>地址：<?= $i['address'] ? $i['address'] : '-'; ?>')" onmouseout="wsug(event, 0)"><?= $i['name'] ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif ?>
</div>
<!--//学院分会 -->