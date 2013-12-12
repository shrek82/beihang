<style type="text/css">
    #nicemenu li {
        display:inline;
        position:relative;
    }
    #nicemenu li span {
        position:relative;
        z-index:10;
    }

    #nicemenu img.arrow {
        cursor:pointer;
    }
    #nicemenu div.sub_menu {
        z-index:10;
        display:none;
        position:absolute;
        text-align: left;
        left:-100px;
        top:0px;
        margin-top:16px;
        border:solid 1px #ccc;
        padding:4px;
        top:2px;
        width:160px;
        background:#fff;
        box-shadow:0 1px 3px rgba(0, 0, 0, 0.4);
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
    }
    * html #nicemenu div.sub_menu {
        margin-top:23px;
    }
    *+html #nicemenu div.sub_menu {
        margin-top:23px;
    }
    #nicemenu div.sub_menu a:link,
    #nicemenu div.sub_menu a:visited{
        color:#01478F;
        display:block;
        font-size:12px;
        padding:4px;
    }
    #nicemenu div.sub_menu a:hover {
        color:#01478F;
        background-color: #f7f7f7;
        text-decoration: none;
    }
    #nicemenu a.item_line {
        border-top:solid 1px #E5E5E5;
        padding-top:6px!important;
        margin-top:3px;
    }
</style>
<div id="nicemenu">
    <ul>
        <li>欢迎回来！赵建刚</li>
        <li><?= View::factory('inc/user/msg') ?>&nbsp;&nbsp;</li>
        <li><span class="head_menu"><a href="index.html">个人主页</a><img src="/static/images/arrow.png" width="18" height="15" align="top" class="arrow"/></span>
            <div class="sub_menu">
                <a href="index.html">杭州校友会</a>
                <a href="index.html">北京校友会</a>
                <a href="index.html" class="item_line">美食俱乐部</a>
                <a href="index.html">篮球俱乐部</a>
                <a href="index.html">羽毛球俱乐部</a>
                <a href="index.html" class="item_line">2001级化工</a>
            </div>
        </li>
       <li><span class="head_menu"><a href="index.html">退出</a></span></li>
    </ul>
</div>

<script type="text/javascript">

    $(document).ready(function(){

        $("#nicemenu img.arrow").click(function(){

            $("span.head_menu").removeClass('active');

            submenu = $(this).parent().parent().find("div.sub_menu");

            if(submenu.css('display')=="block"){
                $(this).parent().removeClass("active");
                submenu.hide();
                $(this).attr('src','/static/images/arrow_hover.png');
            }else{
                $(this).parent().addClass("active");
                submenu.fadeIn();
                $(this).attr('src','/static/images/arrow_select.png');
            }

            $("div.sub_menu:visible").not(submenu).hide();
            $("#nicemenu img.arrow").not(this).attr('src','/static/images/arrow.png');

        })
        .mouseover(function(){ $(this).attr('src','/static/images/arrow_hover.png'); })
        .mouseout(function(){
            if($(this).parent().parent().find("div.sub_menu").css('display')!="block"){
                $(this).attr('src','/static/images/arrow.png');
            }else{
                $(this).attr('src','/static/images/arrow_select.png');
            }
        });

        $("#nicemenu span.head_menu").mouseover(function(){ $(this).addClass('over')})
        .mouseout(function(){ $(this).removeClass('over') });

        $("#nicemenu div.sub_menu").mouseover(function(){ $(this).fadeIn(); })
        .blur(function(){
            $(this).hide();
            $("span.head_menu").removeClass('active');
        });

        $(document).click(function(event){
            var target = $(event.target);
            if (target.parents("#nicemenu").length == 0) {
                $("#nicemenu span.head_menu").removeClass('active');
                $("#nicemenu div.sub_menu").hide();
                $("#nicemenu img.arrow").attr('src','/static/images/arrow.png');
            }
        });


    });

</script>