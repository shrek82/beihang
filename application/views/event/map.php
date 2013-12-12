<!-- event/map:_body -->

<h4>位置：<?=$address ?></h4><br>




<?php // test.local : ABQIAAAA0tcnjKSulEDYnj-v4DTnLhSN6O-sxzKqXTS22pL8Iuz-w39NGhTMvP15_Rd5RUn2Rd8ySldkt8r6xw   ?>

<script src="http://ditu.google.cn/maps?file=api&v=2&key=ABQIAAAAk1oiffeocfUZnC8DyANyGBRJSdan1UD93mLsFP87a-gW1rTCSxTdArFDD5zaeONmo3T7-PqdpQ9UFQ&sensor=true"
        type="text/javascript">
</script>
<script type="text/javascript" src="http://j.maxmind.com/app/geoip.js"></script>

<div id="google_map_canvas" style="
     width: <?= $w ?>;
     height: <?= $h ?>"></div>


<script type="text/javascript">

    var local_lat = geoip_latitude();
    var local_long = geoip_longitude();

    var map = new GMap2(document.getElementById("google_map_canvas"));
    var geocoder = new GClientGeocoder();
    var marker = null;

    // p1
    geocoder.getLatLng('<?= $address ?>', function(point){
        //map.addControl(new GLargeMapControl()); // 缩放
        map.addControl(new GMapTypeControl()); // 切换
        //map.addMapType(G_PHYSICAL_MAP);
        map.setCenter(point, <?= isset($zoom) ? (int) $zoom : 16 ?>);
        marker = new GMarker(point);
        marker.openInfoWindowHtml("<?= $address ?>");
        GEvent.addListener(marker, "click", function() {
            map.panTo(point);
            marker.openInfoWindowHtml("<?= $address ?>");
        });
        map.addOverlay(marker);
    });

    // p2
    if(local_lat && local_long){
        var p2 = new GLatLng(local_lat, local_long);
        marker2 = new GMarker(p2);
        GEvent.addListener(marker2, "click", function() {
            map.panTo(p2);
            marker2.openInfoWindowHtml("你目前可能所在的位置");
        });
        map.addOverlay(marker2);
    }
</script>