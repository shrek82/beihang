//全局变量
var imageUrls = [], //用于保存从服务器返回的图片信息数组
        selectedImageCount = 0;  //当前已选择的但未上传的图片数量

editor.setOpt({
    imageFieldName: "upfile",
    compressSide: 0,
    maxImageSideLength: 900
});

utils.domReady(function() {

    //修改后的
    window.zuaaUeditorImage = {};
    zuaaUeditorImage.add_watermark = "no";
    zuaaUeditorImage.album_id = 0;

    zuaaUeditorImage.flashOptions = {
        container: "flashContainer", //flash容器id
        url: '/upload/uploadAttachedImage', // 上传处理页面的url地址
        ext: '{"add_watermark":"no"}', //可向服务器提交的自定义参数列表
        fileType: '{"description":"' + lang.fileType + '", "extension":"*.gif;*.jpeg;*.png;*.jpg"}', //上传文件格式限制
        flashUrl: 'imageUploader.swf', //上传用的flash组件地址
        width: 608, //flash的宽度
        height: 250, //flash的高度
        gridWidth: 150, // 每一个预览图片所占的宽度
        gridHeight: 113, // 每一个预览图片所占的高度
        picWidth: 100, // 单张预览图片的宽度
        picHeight: 100, // 单张预览图片的高度
        uploadDataFieldName: 'picdata', // POST请求中图片数据的key
        picDescFieldName: 'pictitle', // POST请求中图片描述的key
        maxSize: 20, // 文件的最大体积,单位M
        compressSize: 2, // 上传前如果图片体积超过该值，会先压缩,单位M
        maxNum: 32, // 单次最大可上传多少个文件
        compressSide: editor.options.compressSide, //等比压缩的基准，0为按照最长边，1为按照宽度，2为按照高度
        compressLength: editor.options.maxImageSideLength        //能接受的最大边长，超过该值Flash会自动等比压缩
    };
    //回调函数集合，支持传递函数名的字符串、函数句柄以及函数本身三种类型
    zuaaUeditorImage.callbacks = {
        // 选择文件的回调
        selectFileCallback: function(selectFiles) {
            utils.each(selectFiles, function(file) {
                var tmp = {};
                tmp.id = file.index;
                tmp.data = {};
                postConfig.push(tmp);
            });
            selectedImageCount += selectFiles.length;
            if (selectedImageCount)
                baidu.g("upload").style.display = "";
            dialog.buttons[0].setDisabled(true); //初始化时置灰确定按钮
        },
        // 删除文件的回调
        deleteFileCallback: function(delFiles) {
            for (var i = 0, len = delFiles.length; i < len; i++) {
                var index = delFiles[i].index;
                postConfig.splice(index, 1);
            }
            selectedImageCount -= delFiles.length;
            if (!selectedImageCount) {
                baidu.g("upload").style.display = "none";
                dialog.buttons[0].setDisabled(false);         //没有选择图片时重新点亮按钮
            }
        },
        // 单个文件上传完成的回调
        uploadCompleteCallback: function(data) {
            try {
                var info = eval("(" + data.info + ")");
                info && imageUrls.push(info);
                selectedImageCount--;
            } catch (e) {
            }

        },
        // 单个文件上传失败的回调,
        uploadErrorCallback: function(data) {
            if (!data.info) {
                alert(lang.netError);
            }
            //console && console.log(data);
        },
        // 全部上传完成时的回调
        allCompleteCallback: function() {
            dialog.buttons[0].setDisabled(false);    //上传完毕后点亮按钮
        },
        // 文件超出限制的最大体积时的回调
        //exceedFileCallback: 'exceedFileCallback',
        // 开始上传某个文件时的回调
        startUploadCallback: function() {
            var config = postConfig.shift();
            //也可以在这里更改
            //if(config.id==2){ //设置第三张图片的对应参数
            //     config.data={"myParam":"value"}
            // }
            flashObj.addCustomizedParams(config.id, config.data);
        }
    };
    imageUploader.init(zuaaUeditorImage.flashOptions, zuaaUeditorImage.callbacks);
    $G("upload").onclick = function() {
        /**
         * 接口imageUploader.setPostParams()可以在提交时设置本次上传提交的参数（包括所有图片）
         * 参数为json对象{"key1":"value1","key2":"value2"}，其中key即为向后台post提交的name，value即为值。
         * 其中有一个特殊的保留key值为action，若设置，可以更改本次提交的处理地址
         */
        var postParams = {
            "dir": baidu.g("savePath").value
        };
        imageUploader.setPostParams(postParams);
        flashObj.upload();
        this.style.display = "none";
        $G("savePath").parentNode.style.display = "none";
    };

});


//
//切换加水印上传组件
function addshuiyin(val) {
    document.getElementById('localFloat').innerHTML = '';
    document.getElementById('remoteFloat').innerHTML = '';
    document.getElementById('flashContainer').innerHTML = '';
    add_watermark = val;
    zuaaUeditorImage.flashOptions.ext = '{"add_watermark":"' + zuaaUeditorImage.add_watermark + '","album_id":"' + zuaaUeditorImage.album_id + '"}';
    imageUploader.init(zuaaUeditorImage.flashOptions, zuaaUeditorImage.callbacks);
}

//查询我加入的组织
function get_albums(cat) {
    var url = "/album/getAlbumIds?cat=" + cat;
    var loading = document.getElementById("loading");
    loading.innerHTML = '<img src="/static/images/user/loading6.gif">';
    jQuery.get(url, function(data) {
        loading.innerHTML = '';
        if (cat == 'my_album') {
            document.getElementById("org_box").innerHTML = '';
            document.getElementById("album_box").innerHTML = data;
        }
        else {
            document.getElementById("album_box").innerHTML = '';
            document.getElementById("org_box").innerHTML = data;
        }
    });
}

function change_org(org_field, id) {
    var loading = document.getElementById("loading");
    loading.innerHTML = '<img src="/static/images/user/loading6.gif">';
    var url = "/album/getAlbumIds?" + org_field + "=" + id;
    jQuery.get(url, function(data) {
        loading.innerHTML = '';
        document.getElementById("album_box").innerHTML = data;
    });
}

//切换相册id
function change_album_id(id) {
    if (id > 0) {
        album_id = id;
    }
    else {
        album_id = 0;
    }
    addshuiyin(zuaaUeditorImage.add_watermark);
}