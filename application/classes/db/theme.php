<?php
/**
  +-----------------------------------------------------------------
 * 名称：地方分会主页风格模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午9:17
  +-----------------------------------------------------------------
 */
class Db_Theme {

    //默认主页风格
    public static function defaultTheme() {
        return array(
            'theme' => 'theme1',
            'background_image' => NULL,
            'background_color' => NULL,
            'usercustom' => 0,
            'banner_limit' => 5,
            'news_limit' => 5,
            'weibo_limit' => 10,
            'event_limit' => 8,
            'bbsunit_limit' => 10,
            'allow_post_weibo' => True,
            'weibo_topic' => NULL
        );
    }

//获取主题风格
    public static function getTheme($condition) {

        //默认皮肤
        $default_theme=self::defaultTheme();

        //查询数据库皮肤
        $sql = DB::select(DB::expr('t.*'))->from(array('theme', 't'));
        if (isset($condition['aa_id'])) {
            $default_theme['aa_id']=$condition['aa_id'];
            $sql = $sql->where('aa_id', '=', $condition['aa_id']);
        }
        elseif (isset($condition['club_id'])) {
            $default_theme['club_id']=$condition['club_id'];
            $sql = $sql->where('club_id', '=', $condition['club_id']);
        }
        elseif (isset($condition['classroom_id'])) {
            $sql = $sql->where('classroom_id', '=', $condition['classroom_id']);
        }
        else {
            return false;
        }
        $theme = $sql->execute()->as_array();

        //有皮肤记录
        if($theme){
            return $theme[0];
        }
        //校友会没有样式增加样式
        elseif(isset($condition['aa_id'])){
            $theme=new Theme();
            $theme->fromArray($default_theme);
            $theme->save();
            return $default_theme;
        }
        //俱乐部没有样式增加样式
        elseif(isset($condition['club_id'])){
            $theme=new Theme();
            $default_theme['news_limit']=0;
            $theme->fromArray($default_theme);
            $theme->save();
            return $default_theme;
        }
        //班级等不增加数据库样式
        else{
            return self::defaultTheme();
        }
    }

}

?>
