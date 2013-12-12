<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>无标题文档</title>
<style type="text/css">
<!--
body,td{font-size:12px;

}
-->
</style></head>

<body>

<DIV id="demo" style="OVERFLOW: hidden;width:100%">
   <TABLE border="0" cellPadding="0" cellspace="0" align="center">
    <TR>
     <TD id=demo1 vAlign="top" width="135">
      <TABLE width="100%" border=0 cellSpacing="0" cellPadding="0" align="center">
        <TR>
<?php foreach($project as $p): ?>
 <TD>
     <a href="<?=URL::site('donate/view?id='.$p['id'])?>" target="_blank">
<img src="<?= $p['img_path'] ?>"  width="100" height="75" hspace="4" border="0" align="absmiddle" ></a></TD>
 <?php endforeach;?>

        </TR>
     </TABLE>
    </TD>
    <TD id="demo2" width="12" vAlign="top"></TD>
   </TR>
 </TABLE>
</DIV>
    <script type="text/javascript">
  var speed=10//速度数值越大速度越慢
  demo2.innerHTML=demo1.innerHTML
  function Marquee(){
  if(demo2.offsetWidth-demo.scrollLeft<=0)
  demo.scrollLeft-=demo1.offsetWidth
  else{
  demo.scrollLeft++
  }
  }
  var MyMar=setInterval(Marquee,speed)
  demo.onmouseover=function() {clearInterval(MyMar)}
  demo.onmouseout=function() {MyMar=setInterval(Marquee,speed)}
</script>
<script type="text/javascript">
var win= null;
function NewWindow(mypage,myname,w,h,scroll){
var winl = (screen.width-w)/2;
var wint = (screen.height-h)/2;
var settings ='height='+h+',';
settings +='width='+w+',';
settings +='top=0,';
settings +='left='+winl+',';
settings +='scrollbars='+scroll+',';
settings +='resizable=yes';
win=window.open(mypage,myname,settings);
if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}
function MM_openBrWindow(theURL,winName,features) {
window.open(theURL,winName,features);
}
</script>
</body>
</html>