<style type="text/css">
#alumni_n_asso{
    margin-top: 5px;
} #alumni_n_asso a.cont_tab{
    display: block;
    padding: 4px 10px;
    float: left;
} #alumni_n_asso a.cur{
    background: #3e5ba5;
    color: #fff;
    font-weight: bold;
}
#ana_container{
}
.ana_bar, .ana_bar2{ padding: 4px 10px; font-weight: bold }
.ana_bar{ background: #999; color: #fff; }
.ana_bar2{ background: #eee; color: #333; }
.ana_cnt{ margin-top: 0; clear: left }
</style>

<div id="alumni_n_asso">
    <!--
    <div style="height: 26px; overflow: hidden">
    <a href="#" onmouseover="ana_tab('alumni')" rel="alumni" class="cont_tab cur">北航校友</a>
    <a href="#" onmouseover="ana_tab('aa')" rel="aa" class="cont_tab">校友会</a>
    <a href="#" onmouseover="ana_tab('club')" rel="club" class="cont_tab">俱乐部</a>
    </div> -->

    <div id="ana_container">

        <div id="tab_for_alumni" class="ana_cnt">
            <div class="bar candyCorner">院士风采</div>
            <?= View::factory('inc/main/people') ?>
            <div class="bar candyCorner">受关注最多的校友</div>
            <?= View::factory('inc/main/concern') ?>
            <div class="bar candyCorner">周发帖排行</div>
            <?= View::factory('inc/main/unitRank') ?>
        </div>


    </div>
</div>

<script type="text/javascript">
    /*
function ana_tab(tab){
    $$('.ana_cnt').addClass('hide');
    $('tab_for_'+tab).removeClass('hide');
    $$('.cont_tab').removeClass('cur');
    $$('.cont_tab[rel='+tab+']').addClass('cur');
} ana_tab('alumni');*/
</script>