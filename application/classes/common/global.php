<?php
/**
  +-----------------------------------------------------------------
 * 名称：公共方法
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：
  +-----------------------------------------------------------------
 */
//全站有用的函数
class Common_Global {

    //验证email格式
    public static function email($email) {
        $expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD';
        return (bool) preg_match($expression, (string) $email);
    }

  //转换微博话题
    public static function topic($content) {
        $tag_pattern = "/\#([^\#|.]+)\#/";
        preg_match_all($tag_pattern, $content, $tagsarr);
        $tags = implode(',', $tagsarr[1]);
        $content = preg_replace($tag_pattern, '<a href="/weibo_topic?name=#${1}#">#${1}#</a>', $content);
        return $content;
    }

     //简单格式化
    public static function weibotext($content) {
        $content = preg_replace('/#/', '', $content);
        $content = preg_replace('/\[u=([\d]+)\]/', '', $content);
        $content = preg_replace('/\[\/u]/', '', $content);
        $content = preg_replace('/\[e=([\d]+)\]/', '', $content);
        $content = preg_replace('/\[\/e]/', '', $content);
        return $content;
    }

//发布到新浪微博的text
    public static function sinatext($content) {
        $content = preg_replace('/@/', '', $content);
        $content = preg_replace('/\[u=([\d]+)\]/', '', $content);
        $content = preg_replace('/\[\/u]/', '', $content);
        $content = preg_replace('/\[e=([\d]+)\]/', '', $content);
        $content = preg_replace('/\[\/e]/', '', $content);
        return $content;
    }

   //转换微博评论
    public static function weibohtml($content, $aid, $limit = null) {

        if ($limit) {
            $content = Text::limit_chars($content, $limit);
        }

        $content = nl2br($content);

        //转换话题
        $tag_pattern = "/\#([^\#|.]+)\#/";
        preg_match_all($tag_pattern, $content, $tagsarr);
        $tags = implode(',', $tagsarr[1]);
        $content = preg_replace($tag_pattern, '<a href="/weibo?id=' . $aid . '&topic=$1">#${1}#</a>', $content);

        //转换用户链接
        $content = preg_replace('/\[u=([\d]+)\](.*)\[\/u\]/U', '<a href="/weibo?id=' . $aid . '&uid=$1">@$2</a>', $content);

        //转换活动链接
        $content = preg_replace('/\[e=([\d]+)\](.*)\[\/e\]/U', '<a href="/event/view?id=$1" target="_blank">$2</a>', $content);

        //转换表情
        if (preg_match('/\[.*\]/U', $content)) {
            $exparray = Kohana::config('expression.default');
            foreach ($exparray as $name => $path) {
                $content = str_replace('[' . $name . ']', '<img src="/static/homepage/expression/' . $path . '" title="' . $name . '" class="exp">', $content);
            }
        }

        //Emotion::autoToUrl($content);
        return Emotion::autoToUrl($content);
    }

//生成JSON数据
    public static function arrayToJson($array) {
        echo json_encode($array);
    }

//过滤为手机可显示的普通文本格式
    public static function mobileText($content) {
        $content = preg_replace('/(<img.+?src=")[^"]+(\/.+?>)/', '[图片]', $content);
        $content = strip_tags($content); //过滤html标签
        $content = preg_replace("/^\n+/", "", $content); //去掉最开始回车
        $content = preg_replace("/\n{2,10}/", "\n", $content); //多行换行符替换为一个
        $content = preg_replace("/&nbsp;/", "", $content); //过滤多余空格
        $content = preg_replace("/ /", "", $content); //去除默认空白或缩进
        $content = preg_replace("/\s+/", "", $content); //去除默认空白或缩进
        $content = preg_replace("/　/", "", $content); //去除默认空白或缩进
        return $content;
    }

    //过滤本站附件绝对路径
    public static function filterAbsolutePath($str) {
        if ($str) {
            $str = str_replace('http://localhost/static/upload/', '/static/upload/', $str);
            $replace_str = 'http://' . $_SERVER['HTTP_HOST'] . '/static/upload/';
            $str = str_replace($replace_str, '/static/upload/', $str);
            $str = str_replace('/static/upload/', 'static/upload/', $str);
            return $str;
        } else {
            return false;
        }
    }

    //获取本站图集
    //会将本站所有绝对路径转化为相对路径
    public static function getImages($str, $needle) {
        $pattern = "/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/i";
        preg_match_all($pattern, $str, $imgArray);
        $pics = array();
        if (count($imgArray[1]) > 0) {
            if ($needle) {
                foreach ($imgArray[1] AS $key => $url) {
                    if (strstr($url, $needle)) {
                        $pics[] = self::filterAbsolutePath($url);
                    }
                }
            } else {
                foreach ($imgArray[1] AS $key => $url) {
                    $pics[] = self::filterAbsolutePath($url);
                }
            }
            return $pics;
        } else {
            return array();
        }
    }

    //获取图片相对地址
    public static function getImageBysuffix($original_path, $file_suffix, $reset = false) {

        if (strstr($original_path, 'static/upload')) {
            //过滤绝对路径
            $original_path = str_replace('http://localhost/static/upload/', '/static/upload/', $original_path);
            $replace_str = 'http://' . $_SERVER['HTTP_HOST'] . '/static/upload/';
            //获取原图地址
            $original_path = str_replace($replace_str, '/static/upload/', $original_path);
            $original_path = str_replace('/static/upload/', 'static/upload/', $original_path);
            $original_path = str_replace('_thumbnail', '', $original_path);
            $original_path = str_replace('_bmiddle', '', $original_path);
            $original_path = str_replace('_original', '', $original_path);
            $original_path = str_replace('_mini', '', $original_path);
            //目标图片名称
            $imageName = basename($original_path);
            $suffix = substr($imageName, stripos($imageName, '.'));
            if ($file_suffix) {
                $targetFileName = str_replace($suffix, '', $original_path) . '_' . $file_suffix . $suffix;
            }
            //目标为原图
            else {
                $targetFileName = $original_path;
            }

            //其实应该执行下去保证有图片的，可是每次判断速度太慢了
            //return $targetFileName;
            //上传配置
            $config = array(
                'mini' => array('width' => 150, 'height' => 150),
                'thumbnail' => array('width' => 320, 'height' => 320),
                'bmiddle' => array('width' => 640, 'height' => 640),
                'original' => array('width' => 1000, 'height' => 1000)
            );

            //判断文件是否存在
            if (is_file($targetFileName) AND $reset == false) {
                return $targetFileName;
            }
            //不存在根据原图生产规格尺寸的图片
            elseif (isset($config[$file_suffix]) AND is_file($original_path)) {
                //获取原图属性
                $original_file = Image::factory($original_path);
                $w = $original_file->width;
                $h = $original_file->height;
                if ($w > $config[$file_suffix]['width']) {
                    Image::factory($original_path)
                            ->resize($config[$file_suffix]['width'], $config[$file_suffix]['height'])
                            ->save($targetFileName);
                } else {
                    $targetFileName = $original_path;
                }
                return $targetFileName;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //过滤为智能手机可显示html的格式
    //图片都为网络绝对地址
    public static function standardHtmlAndPics($str, $def_img_name = null, $absolute_path = true,$script=true) {

        //图片集
        $pics = array();

        //本站地址
        $http_host = $_SERVER['HTTP_HOST'];

        $webiste = 'http://' . $http_host;

        //修改图片地址
        $replace_str = 'src="http://' . $http_host . '/static';
        //本站图片表示

        $str = str_replace('<br>', '<br />', $str);
        $str = str_replace('<br /><br />', '<br />', $str);
        $str = str_replace('<br /></p>', '</p>', $str);
        $str = str_replace('<p><br />', '<p>', $str);

        $str = preg_replace('/src="\/static/', $replace_str, $str);
        $pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern, $str, $imgArray);

        //标记图片
        $str = preg_replace('/(<img.+?src=")[^"]+(\/.+?>)/', '_image_', $str);

        //去除html标记属性
        $str = preg_replace("/<([a-zA-Z]+)[^>]*>/", "<\\1>", $str);

        $image_count = count($imgArray[1]);

        $included_image = False;

        if ($image_count > 0) {

            //本站图片序号
            $nid = 1;

            foreach ($imgArray[1] AS $key => $url) {
                //本站图片附件
                if (strstr($url, 'static/upload/attached/') OR strstr($url, 'static/upload/pic/')) {
                    $pics[$key]['nid'] = 'nid' . $nid;
                    $pics[$key]['pic_id'] = '';
                    $pics[$key]['title'] = $def_img_name ? $def_img_name : '图片展示';
                    $pics[$key]['intro'] = '暂无图片描述';
                    $mini_pic = self::getImageBysuffix($url, 'mini');
                    $pics[$key]['mini_pic'] = $absolute_path ? $webiste . '/' . $mini_pic : $mini_pic;
                    $thumbnail_pic = str_replace('_mini', '_thumbnail', $pics[$key]['mini_pic']);
                    $pics[$key]['thumbnail_pic'] = $thumbnail_pic;
                    $bmiddle_pic = str_replace('_thumbnail', '_bmiddle', $pics[$key]['thumbnail_pic']);
                    $pics[$key]['bmiddle_pic'] = $bmiddle_pic;
                    $original_pic = $pics[$key]['bmiddle_pic'];
                    $pics[$key]['original_pic'] = $original_pic;

                    //正文图片使用缩略图
                    if (!$included_image) {
                        $included_image = True;
                        $loadAllLink = '<div style="text-align:center;margin:8px 0;" id="loading_button"><label onclick="loadAllImage()" style="color:#346AD3">查看所有图片</label></div>';
                        $imgTag = '<div style="text-align:center;margin:5px 0;" id="nid' . $nid . '"><a href="' . $pics[$key]['bmiddle_pic'] . '" id="nid' . $nid . '" ><img src="' . $pics[$key]['thumbnail_pic'] . '" style="width:250px;border-width:0;margin:5px 0"></a></div>';
                        $imgTag = $image_count > 1 ? $loadAllLink . $imgTag : $imgTag;
                        $str = preg_replace("/_image_/", $imgTag, $str, 1);
                    } else {
                        $imgTag = '<div style="text-align:center;margin:5px 0;" id="nid' . $nid . '"><label onclick="loadAPic(' . $nid . ')" style="color:#346AD3">查看图片</label></div>';
                        $str = preg_replace("/_image_/", $imgTag, $str, 1);
                    }

                    $nid+=1;
                }
                //本站其他图片（可能为表情）
                elseif (strstr($url, $webiste)) {
                    $imgTag = '<img src="' . $url . '" >';
                    $str = preg_replace("/_image_/", $imgTag, $str, 1);
                }
                //外来图片
                else {
                    $str = preg_replace("/_image_/", '', $str, 1);
                }
            }
        }

        //多个换行符替换成1个
        $str = str_replace('<br />', '<br />&nbsp;&nbsp;&nbsp;&nbsp;', $str);
        $str = str_replace('<span>', '', $str);
        $str = str_replace('</span>', '', $str);
        $str = str_replace('<o></o:p>', '', $str);

        //图片脚本
        if (count($pics) > 0 AND $script) {
            $img_array_str = '<script type="text/javascript">var img_html_array=[';
            foreach ($pics AS $key => $p) {
                $a_img = '\'<a href="' . $p['bmiddle_pic'] . '"><img src="' . $p['thumbnail_pic'] . '"  style="width:250px"></a>\'';
                $img_array_str = $img_array_str . $a_img . ',';
            }
            $str = $str . $img_array_str . '];</script>';
        }

        return array('html' => $str, 'pics' => $pics);
    }

    //返回个人主页或微博地址
    public static function imgLink($urls) {
        if (trim($urls)) {
            $url_array = explode("\n", trim($urls));
            $back = '';
            foreach ($url_array AS $url) {
                if ($url) {
                    if (strpos($url, 'weibo.com') OR strpos($url, 't.sina.com')) {
                        $link = '<a href="' . $url . '" target="_blank" title="新浪微博"><img src="/static/ico/logo/t_sina.gif"></a>';
                    } elseif (strpos($url, 't.qq.com')) {
                        $link = '<a href="' . $url . '" target="_blank"  title="腾讯微博"><img src="/static/ico/logo/t_qq.gif"></a>';
                    } elseif (strpos($url, 't.163.com')) {
                        $link = '<a href="' . $url . '" target="_blank" title="网易微博"><img src="/static/ico/logo/t_163.gif"></a>';
                    } elseif (strpos($url, 't.sohu.com')) {
                        $link = '<a href="' . $url . '" target="_blank"  title="搜狐微博"><img src="/static/ico/logo/t_sohu.gif"></a>';
                    } elseif (strpos($url, 'renren.com')) {
                        $link = '<a href="' . $url . '" target="_blank"  title="人人主页"><img src="/static/ico/logo/t_renren.gif"></a>';
                    } elseif (strpos($url, 'baidu.com')) {
                        $link = '<a href="' . $url . '" target="_blank" title="百度空间"><img src="/static/ico/logo/baidu.gif"></a>';
                    } elseif (strpos($url, 'kaixin.com')) {
                        $link = '<a href="' . $url . '" target="_blank" title="开心网"><img src="/static/ico/logo/t_kaixin.gif"></a>';
                    } elseif (strpos($url, 'douban.com')) {
                        $link = '<a href="' . $url . '" target="_blank"  title="豆瓣网"><img src="/static/ico/logo/douban.gif"></a>';
                    } elseif (strpos($url, 'facebook.com')) {
                        $link = '<a href="' . $url . '" target="_blank" title="facebook"><img src="/static/ico/logo/facebook.gif"></a>';
                    } else {
                        $link = '<a href="' . $url . '" target="_blank" title="进入网站" ><img src="/static/ico/logo/other.png"></a>';
                    }
                    $back = $back ? $back . '&nbsp;' . $link : $link;
                }
            }
            return $back;
        }
    }

}

?>