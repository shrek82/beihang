<?php
/**
  +-----------------------------------------------------------------
 * 名称：上传方法
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:52
  +-----------------------------------------------------------------
 */
class Common_Upload {

    //上传附件
    public static function attachedfile($file_data=null, $fileSize=150) {

        //上传配置
        $config = array(
            "fileType" => array(".rar", ".zip", ".doc", ".docx", '.xls', '.wps', ".pdf", ".txt", ".swf", ".wmv", '.jpg', '.jpeg', '.mp3', '.ppt', '.pptx', '.avi', '.rmvb', '.wmv', '.flv', '.rm', '.psd'), //文件允许格式
            'fileSize' => $fileSize, //文件大小限制，单位M
        );

        //文件上传状态
        $successed = false;
        $error = false;

        //自动创建本地目录
        if (!is_dir('static/upload/attached/' . date('Ym'))) {
            mkdir('static/upload/attached/' . date('Ym'), 0777);
        }

        //有数据
        if ($file_data) {
            $clientFile = $file_data;
            $old_file_name = $clientFile['name'];

            //格式文件验证
            $file_extend = strtolower(strrchr($clientFile["name"], '.'));
            if (!in_array($file_extend, $config['fileType'])) {
                $error = "不支持的文件类型！";
            }

            //验证文件大小
            $file_size = 1024 * 1024 * $config['fileSize'];
            if ($clientFile["size"] > $file_size) {
                $error = "文件大小超出限制！";
            }

            //保存目录
            $path = 'static/upload/attached/' . date('Ym') . '/';

            //文件名称(无后缀)
            $file_name = date("YmjHis") . rand(10, 1000);

            //上传后未处理的文件路径
            $upload_file_path = $path . $file_name . $file_extend;

            //可以上传
            if (!$error) {
                Upload::save($clientFile, $file_name . $file_extend, $path);
                //替换掉所有类似../和./等相对路径标识
                $file = str_replace('../', '', $upload_file_path);
                return array(
                    'successed' => true,
                    'file_path' => $upload_file_path,
                    'file_extend' => $file_extend,
                    'file_name' => $old_file_name,
                );
            } else {
                return array(
                    'successed' => false,
                    'error' => $error,
                );
            }
        } else {
            return array(
                'successed' => false,
                'error' => '没有接收到任何数据',
            );
        }
    }

    //上传图片
    public static function pic($file_data = null, $user_id = null, $add_watermark = false, $resize =array()) {

        //上传配置
        $config = array(
            'fileType' => array(".gif", ".png", ".jpg", ".jpeg", ".bmp"), //文件允许格式
            'fileSize' => 50, //文件大小限制，单位M
            'mini' => array('width' => 100, 'height' => 100),
            'thumbnail' => array('width' => 150, 'height' => 150),
            'bmiddle' => array('width' => 640, 'height' => 640),
            'original' => array('width' => 1000, 'height' => 1000)
        );

        //设置自定义了图片规格，则覆盖原大小规格
        if ($resize AND is_array($resize)) {
            $config = array_merge($config,$resize);
        }

        $original_name = 'none';
        $error = false;
        $images = array();

        //是否添加水印
        $add_watermark = $add_watermark;

        //按日存放照片
        if (!is_dir('static/upload/pic/' . date('Ym') . '/' . date('d'))) {
            if (!is_dir('static/upload/pic/' . date('Ym'))) {
                mkdir('static/upload/pic/' . date('Ym'), 0777);
            }
            mkdir('static/upload/pic/' . date('Ym') . '/' . date('d'), 0777);
        }

        if ($file_data) {

            //格式文件验证
            $file_extend = strtolower(strrchr($file_data["name"], '.'));
            if (!in_array($file_extend, $config['fileType'])) {
                $error = "不支持的图片类型！";
            }

            $original_name = basename($file_data["name"], $file_extend);

            //验证文件大小
            $file_size = 1024 * 1024 * $config['fileSize'];
            if ($file_data["size"] > $file_size) {
                $error = "图片大小超出最大限制！";
            }

            //保存目录
            $path = 'static/upload/pic/' . date('Ym') . '/' . date('d') . '/';

            //文件名称(无后缀)
            $file_name = ($user_id + 1987) . date("YmdHis") . rand(10, 1000);

            //上传后未处理的文件路径
            $upload_file_path = $path . $file_name . $file_extend;

            //处理后返回文件路径
            $return_file_path = '';

            //可以上传
            if (!$error) {

                //临时保存到目录
                Upload::save($file_data, $file_name . $file_extend, $path);

                //获取原图属性
                $file = Image::factory($upload_file_path);
                $w = $file->width;
                $h = $file->height;

                //生产一张中等大小图片(也是返回后使用的图片)
                $return_file_path = $path . $file_name . '_bmiddle' . $file_extend;

                if ($w > $config['bmiddle']['width']) {
                    Image::factory($upload_file_path)
                            ->resize($config['bmiddle']['width'], $config['bmiddle']['height'])
                            ->save($return_file_path);
                } else {
                    copy($upload_file_path, $path . $file_name . '_bmiddle' . $file_extend);
                }
                $images['bmiddle'] = $path . $file_name . '_bmiddle' . $file_extend;

                //生成一张缩略图
                if ($w > $config['thumbnail']['width']) {
                    Image::factory($upload_file_path)
                            ->resize($config['thumbnail']['width'], $config['thumbnail']['height'])
                            ->save($path . $file_name . '_thumbnail' . $file_extend);
                } else {
                    copy($upload_file_path, $path . $file_name . '_thumbnail' . $file_extend);
                }
                $images['thumbnail'] = $path . $file_name . '_thumbnail' . $file_extend;

                //生成一张迷你图
                if ($w > $config['mini']['width']) {
                    Image::factory($upload_file_path)
                            ->resize($config['mini']['width'], $config['mini']['height'])
                            ->save($path . $file_name . '_mini' . $file_extend);
                } else {
                    copy($upload_file_path, $path . $file_name . '_mini' . $file_extend);
                }
                $images['mini'] = $path . $file_name . '_mini' . $file_extend;

                //自动裁剪最大图片
                $original_file_path = $path . $file_name . $file_extend;
                if ($w > $config['original']['width']) {
                    Image::factory($upload_file_path)
                            ->resize($config['original']['width'], $config['original']['height'])
                            ->save($original_file_path);
                }
                $images['original'] = $original_file_path;

                //给图片添加水印
                if ($add_watermark) {
                    $waterImage = "static/water.png";
                    self::imageWaterMark($return_file_path, 5, $waterImage);
                }

                //返回数据
                return array(
                    'successed' => true,
                    'name' => $original_name,
                    'images' => $images,
                );
            }
            //上传失败
            else {
                return array(
                    'successed' => false,
                    'error' => $error,
                );
            }
        }
        //上传失败
        else {
            return array(
                'successed' => false,
                'error' => '未接受到任何图片数据',
            );
        }
    }

    /*
     * 功能：PHP图片水印 (水印支持图片或文字)
     * 参数：
     *      $groundImage    背景图片，即需要加水印的图片，暂只支持GIF,JPG,PNG格式；
     *      $waterPos       水印位置，有10种状态，0为随机位置；
     *                      1为顶端居左，2为顶端居中，3为顶端居右；
     *                      4为中部居左，5为中部居中，6为中部居右；
     *                      7为底端居左，8为底端居中，9为底端居右；
     *      $waterImage     图片水印，即作为水印的图片，暂只支持GIF,JPG,PNG格式；
     *      $waterText      文字水印，即把文字作为为水印，支持ASCII码，不支持中文；
     *      $textFont       文字大小，值为1、2、3、4或5，默认为5；
     *      $textColor      文字颜色，值为十六进制颜色值，默认为#FF0000(红色)；
     *
     * 注意：Support GD 2.0，Support FreeType、GIF Read、GIF Create、JPG 、PNG
     *      $waterImage 和 $waterText 最好不要同时使用，选其中之一即可，优先使用 $waterImage。
     *      当$waterImage有效时，参数$waterString、$stringFont、$stringColor均不生效。
     *      加水印后的图片的文件名和 $groundImage 一样。
     */

    public static function imageWaterMark($groundImage, $waterPos=0, $waterImage="", $waterText="", $textFont=20, $textColor="#FF0000") {
        $isWaterImage = FALSE;
        //偏移
        $offset = 25;
        $formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。";

        //读取水印文件
        if (!empty($waterImage) && file_exists($waterImage)) {
            $isWaterImage = TRUE;
            $water_info = getimagesize($waterImage);
            $water_w = $water_info[0]; //取得水印图片的宽
            $water_h = $water_info[1]; //取得水印图片的高

            switch ($water_info[2]) {   //取得水印图片的格式
                case 1:$water_im = imagecreatefromgif($waterImage);
                    break;
                case 2:$water_im = imagecreatefromjpeg($waterImage);
                    break;
                case 3:$water_im = imagecreatefrompng($waterImage);
                    break;
                default:die($formatMsg);
            }
        }

        //读取背景图片
        if (!empty($groundImage) && file_exists($groundImage)) {
            $ground_info = getimagesize($groundImage);
            $ground_w = $ground_info[0]; //取得背景图片的宽
            $ground_h = $ground_info[1]; //取得背景图片的高

            switch ($ground_info[2]) {   //取得背景图片的格式
                case 1:$ground_im = imagecreatefromgif($groundImage);
                    break;
                case 2:$ground_im = imagecreatefromjpeg($groundImage);
                    break;
                case 3:$ground_im = imagecreatefrompng($groundImage);
                    break;
                default:die($formatMsg);
            }
        } else {
            die("需要加水印的图片不存在！");
        }

        if (($ground_w < $water_w) || ($ground_h < $water_h)) {
            die("很抱歉，上传图片尺寸太小了，暂时无法添加水印!");
            return;
        }

        //水印位置
        if ($isWaterImage) { //图片水印
            $w = $water_w;
            $h = $water_h;
            $label = "图片的";
        } else {  //文字水印
            $temp = imagettfbbox(ceil($textFont * 2.5), 0, "./MSYH.TTF", $waterText); //取得使用 TrueType 字体的文本的范围
            $w = $temp[2] - $temp[6];
            $h = $temp[3] - $temp[7];
            unset($temp);
            $label = "文字区域";
        }

        switch ($waterPos) {
            case 0://随机
                $posX = rand(0, ($ground_w - $w));
                $posY = rand(0, ($ground_h - $h));
                break;
            case 1://左上
                $posX = $offset; //0+$offset
                $posY = $offset;
                break;
            case 2://左上
                $posX = $ground_w - $w - $offset;
                $posY = 0 + $offset;
                break;
            case 3://中间
                $posX = ($ground_w - $w) / 2;
                $posY = ($ground_h - $h) / 2;
                break;
            case 4://左下
                $posX = 0 + $offset;
                $posY = $ground_h - $h - $offset;
                break;
            case 5://右下
                $posX = $ground_w - $w - $offset;
                $posY = $ground_h - $h - $offset;
                break;
            default://随机
                $posX = rand(0, ($ground_w - $w));
                $posY = rand(0, ($ground_h - $h));
                break;
        }

        //设定图像的混色模式
        imagealphablending($ground_im, true);

        if ($isWaterImage) { //图片水印
            imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h); //拷贝水印到目标文件
        } else {//文字水印
            if (!empty($textColor) && (strlen($textColor) == 7)) {
                $R = hexdec(substr($textColor, 1, 2));
                $G = hexdec(substr($textColor, 3, 2));
                $B = hexdec(substr($textColor, 5));
            } else {
                die("水印文字颜色格式不正确！");
            }
            imagestring($ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));
        }

        //生成水印后的图片
        @unlink($groundImage);
        switch ($ground_info[2]) {//取得背景图片的格式
            case 1:imagegif($ground_im, $groundImage);
                break;
            case 2:imagejpeg($ground_im, $groundImage);
                break;
            case 3:imagepng($ground_im, $groundImage);
                break;
            default:die($errorMsg);
        }

        //释放内存
        if (isset($water_info))
            unset($water_info);
        if (isset($water_im))
            imagedestroy($water_im);
        unset($ground_info);
        imagedestroy($ground_im);
    }

}

?>