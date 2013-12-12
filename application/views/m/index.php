<!-- m/index:_body -->
<div class="ul_box">
    <ul>
        <?php  foreach ($news as $n):?>
        <li><a href="/news/view?id=<?=$n['id']?>"><?=$n['title']?></a></li>
        <?php endforeach;?>
    </ul>
</div>