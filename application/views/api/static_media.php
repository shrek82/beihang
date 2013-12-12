<style type="text/css">
    body{ background: #F6F6F6; font-family: verdana}
    .user_box{height:50px;color: #666;font-size: 12px;border-bottom:1px solid #DAD8D8}
    .cmt_box{border-bottom:1px solid #E8E8E8; padding:5px 0 10px 0;}
    .author_box{height:18px;font-size: 12px}
    .author{float: left;width: 75%;color:#3E71C6;}
    .cmt_content{ font-size: 14px;}
    .post_date{width: 20%;color:#999;float:right; text-align: right}
    .nodata{ color: #999;font-size: 12px}
    .emotion{vertical-align: middle}
</style>
<script type="text/javascript">
    function loadAPic(i){
        var j=i-1;
        document.getElementById("nid"+i).innerHTML=img_html_array[j];
    }
    function loadAllImage(){
        for(i in img_html_array){
            document.getElementById("nid"+(parseInt(i)+1)).innerHTML=img_html_array[i];
        }
        document.getElementById("loading_button").innerHTML="";
    }
</script>