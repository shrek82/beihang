ddddddddddddddd
<div id="msgBox"></div>
<script>
    var msgBox = document.getElementById('msg_box');
    window.Notifications = window.Notifications || window.webkitNotifications;

    if (!window.Notifications) {
    }

    function showNotification() {
        var newNotification = Notifications.createNotification(
                '/static/upload/avatar/48/19.jpg',
                '360极速浏览器',
                '欢迎您使用360极速浏览器HTML5实验室！');
        newNotification.ondisplay = function() {
            var temp = this;
            var fn = function() {
                temp.cancel();
            };
            window.setTimeout(fn, 20000);
        };
        newNotification.show();
    }

    function checkPermission() {
        var permissionLevel = window.Notifications.checkPermission();

        if (permissionLevel == 0) {  // PERMISSION_ALLOWED
            //msgBox.innerHTML = '<b>您已授权此页面显示网页通知！</b><br>若您并没有看到任何网页通知，则表示您的浏览器对网页通知特性的实现存在问题！';
            //msgBox.style.display = 'block';
            showNotification();
        } else if (permissionLevel == 1) {  // PERMISSION_NOT_ALLOWED
            //msgBox.innerHTML = '<b>请点击“允许”按钮，授权此页面显示网页通知！</b><br>若您未看到任何提示条或对话框请求授权，则表示您的浏览器不支持网页通知特性！';
            //msgBox.style.display = 'block';
            window.Notifications.requestPermission(checkPermission);
        } else {  // PERMISSION_DENIED
           // msgBox.innerHTML = '<b>您已选择禁止本页面显示网页通知！</b><br>若您从未看到任何提示条或对话框请求授权，则表示您的浏览器不支持网页通知特性！';
           // msgBox.style.display = 'block';
        }
    }

    //showNotification();
    checkPermission();
</script>



