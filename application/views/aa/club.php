<!--main -->
<script type="text/javascript">
    function setTab(name,cursel,n){
        for(i=1;i<=n;i++){
            var menu=document.getElementById(name+i);
            var con=document.getElementById("con_"+name+"_"+i);
            menu.className=i==cursel?"cur":"";
            con.style.display=i==cursel?"block":"none";
        }
    }
</script>
<p><img src="/static/images/aa_title.gif"></p>

<div class="blue_tab" style="margin: 15px 20px">
    <ul>
        <li><a href="<?= URL::site('aa') ?>" id="one1"  >校友总会</a></li>
        <li><a href="<?= URL::site('aa/branch') ?>" id="one2"   >地方校友会</a></li>
        <li><a href="<?= URL::site('aa/institute') ?>" id="one3" >院系分会</a></li>
        <li><a href="<?= URL::site('aa/club') ?>" id="one4" class="cur">俱乐部</a></li>
    </ul>
</div>

<!--俱乐部 -->
<div id="con_one_4" class="tab_content" >
    <ul class="aa_club">
        <li>稍候呈现，谨请关注！</li>
    </ul>
</div>
<!--//俱乐部 -->