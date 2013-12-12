<?php

/**
  +-----------------------------------------------------------------
 * 名称：校友会模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Aa {

    const SHOW_PATH = 'static/upload/aa/show/';

    static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('gif', 'jpg', 'jpeg', 'png')),
        'Upload::size' => array('1M')
    );

    # 直接加入

    public static function join($aa_id, $user_id, $remarks = null) {
        if (!Db_Aa::isMember($aa_id, $user_id)) {
            $member = new AaMember();
            $member->user_id = $user_id;
            $member->join_at = date('Y-m-d H:i:s');
            $member->visit_at = date('Y-m-d H:i:s');
            $member->remarks = $remarks;
            $member->aa_id = $aa_id;
            $member->save();
            return $member->id;
        }
    }

    # 显示简称

    public static function s($str) {
        $base_config = Kohana::config('config')->base;
        $org_name = $base_config['orgname'];
        if (strstr($str, $org_name)) {
            $str = str_replace($org_name, '', $str);
        } elseif (strstr($str, '校友会')) {
            $str = str_replace('校友会', '', $str);
        }
        return $str;
    }

    # 检查是否有res的管理权限

    public static function checkPow($aa_id, $user_id, $res, $redirect = true) {
        if (!strstr(self::permission($aa_id, $user_id), $res)) {
            if ($redirect) {
                Model_User::deny('很抱歉，您没有该校友会的' . $res . '管理权限');
            } else {
                return FALSE;
            }
        }
        else
            return TRUE;
    }

    public static function permission($aa_id, $user_id) {
        $permission = Doctrine_Query::create()
                ->select('permissions')
                ->from('AaMember')
                ->where('aa_id = ?', $aa_id)
                ->andWhere('user_id = ?', $user_id)
                ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        if (!$permission)
            return '';
        else
            return $permission;
    }

    public static function permissions($type = 'string') {
        $res = Kohana::config('acl.AaResources');
        if ($type == 'string') {
            return implode(' ', $res);
        } else {
            return $res;
        }
    }

    public static function set_chairman($aa_id, $user_id, $title) {
        //原先管理员信息
        $old_chairman = Doctrine_Query::create()
                ->from('AaMember')
                ->where('aa_id = ? AND chairman = ?', array($aa_id, true))
                ->fetchOne();

        //设置校友会管理员为空
        if ($aa_id AND $old_chairman AND $user_id == False) {
            $old_chairman['chairman'] = false;
            $old_chairman['permissions'] = '';
            $old_chairman['manager'] = false;
            $old_chairman['title'] = '';
            $old_chairman->save();
        }

        //修改、添加管理员
        if ($aa_id AND $user_id) {

            //不同帐号情况取消原先管理员
            if ($old_chairman AND $user_id > 0 AND ($old_chairman['user_id'] != $user_id)) {
                $old_chairman['chairman'] = false;
                $old_chairman['permissions'] = '';
                $old_chairman['manager'] = false;
                $old_chairman['title'] = '';
                $old_chairman->save();
            } else {
                //新管理员加入信息
                $new_chairman = Doctrine_Query::create()
                        ->from('AaMember')
                        ->where('aa_id = ?', $aa_id)
                        ->andWhere('user_id = ?', $user_id)
                        ->fetchOne();

                // 没有加入校友会则先加入
                if ($new_chairman == false) {
                    $m = new AaMember();
                    $m->aa_id = $aa_id;
                    $m->chairman = true;
                    $m->manager = true;
                    $m->join_at = date('Y-m-d H:i:s');
                    $m->visit_at = date('Y-m-d H:i:s');
                    $m->title = $title;
                    $m->permissions = self::permissions();
                    $m->title = $title;
                    $m->user_id = $user_id;
                    $m->save();
                }
                //已加入则直接修改为管理员
                else {
                    $new_chairman['chairman'] = true;
                    $new_chairman['permissions'] = self::permissions();
                    $new_chairman['manager'] = true;
                    $new_chairman['title'] = '';
                    $new_chairman->save();
                }
            }
        }
    }

    //获取最近7天热度
    public static function getHot($aa_total_hit = 0, $weekhit = 0) {
        $hot = ceil(($weekhit / $aa_total_hit) * 100);
        if ($hot >= 60) {
            $hot+=30;
        } elseif ($hot < 5) {
            $hot+=50;
        } elseif ($hot < 5) {
            $hot+=50;
        } elseif ($hot >= 10) {
            $hot+=50;
        } elseif ($hot >= 10) {
            $hot+=50;
        } else {
            $hot+=50;
        }
        return $hot . '°C';
    }

    //返回校友会皮肤
    public static function getTheme($aa_id) {
        $theme = Doctrine_Query::create()
                ->from('AaTheme')
                ->where('aa_id = ?', $aa_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        if (!$theme) {
            $theme = new AaTheme();
            $theme->aa_id = $aa_id;
            $theme->theme = 'theme1';
            $theme->banner_limit = 3;
            $theme->news_limit = 5;
            $theme->weibo_limit = 10;
            $theme->event_limit = 8;
            $theme->bbsunit_limit = 8;
            $theme->allow_post_weibo = true;
            //$theme->aaTheme->weibo_topic = '';
            $theme->save();
        }

        return $theme;
    }

    //查找校友会
    public static function searchByCity($city) {
        $city = str_replace('市', '', $city);
        $aa = Doctrine_Query::create()
                ->from('Aa')
                ->where('name LIKE ? OR range LIKE ?',array('%' . $city . '%','%' . $city . '%'))
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        if ($aa) {
            return $aa;
        } else {
            return false;
        }
    }

    //申请用户加入某城市(关键字)校友会
    public static function applyJoinAaForCity($city = null, $user_id = null) {
        if ($city) {
            $aa = self::searchByCity($city);
            //申请加入地方校友会
            if ($aa AND $user_id) {
                Model_Aa::join($aa['id'], $user_id,'根据注册信息系统自动申请加入！');
            }
            return array('aa'=>$aa,'user_id'=>$aa);
        }
    }

}