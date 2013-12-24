<?php

/**
  +-----------------------------------------------------------------
 * 名称：俱乐部模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Club {

    const LOGO_DIR = 'static/upload/club/';

    # 判断是否为本俱乐部成员id

    public static function isMember($club_id, $user_id) {
        return (bool) Doctrine_Query::create()
                        ->from('ClubMember')
                        ->where('club_id = ?', $club_id)
                        ->andWhere('user_id = ?', $user_id)
                        ->count();
    }

    //判断是否为管理员
    public static function isManager($club_id, $user_id) {
        return (bool) Doctrine_Query::create()
                        ->from('ClubMember')
                        ->where('club_id = ?', $club_id)
                        ->andWhere('user_id = ?', $user_id)
                        ->andWhere('manager = ?', true)
                        ->count();
    }

//返回logo地址
    public static function logo($club_id) {
        $file = $club_id . '.jpg';
        $logo_url = URL::base() . self::LOGO_DIR;
        $logo_path = DOCROOT . self::LOGO_DIR;

        if (!file_exists($logo_path . $file)) {
            return $logo_url . 'default.jpg';
        }
        return $logo_url . $file . '?time=' . time();
    }

    public static function isExist($aa_id, $name) {
        return (bool) Doctrine_Query::create()
                        ->from('Club c')
                        ->where('c.aa_id = ?', $aa_id)
                        ->andWhere('c.name = ?', $name)
                        ->count();
    }

    static function set_chairman($club_id, $user_id, $title) {
        $member = Doctrine_Query::create()
                ->from('ClubMember')
                ->where('club_id = ?', $club_id)
                ->andWhere('user_id = ?', $user_id)
                ->fetchOne();

        if ($member == false || $member['chairman'] == false) {
            // 免除原负责人
            $chairman = Doctrine_Query::create()
                    ->from('ClubMember')
                    ->where('club_id = ? AND chairman = ?', array($club_id, true))
                    ->fetchOne();

            if ($chairman != false) {
                $chairman['chairman'] = false;
                $chairman['manager'] = false;
                $chairman['title'] = '';
                $chairman->save();
            }
        }

        if ($member == false) {
            // 设立新负责人
            $m = new ClubMember();
            $m->club_id = $club_id;
            $m->chairman = true;
            $m->manager = true;
            $m->title = $title;
            $m->user_id = $user_id;
            $m->join_at = date('Y-m-d H:i:s');
            $m->visit_at = date('Y-m-d H:i:s');
            $m->save();
        } elseif ($member['chairman'] == false) {
            // 成员转正负责人
            $member['chairman'] = true;
            $chairman['manager'] = true;
            $member['title'] = $title;
            $member->save();
        } elseif ($member['title'] != $title) {
            // 更新头衔
            $member['title'] = $title;
            $member->save();
        }
    }

    //删除俱乐部
    public static function del($cid) {
        if ((int)$cid > 0) {
            Doctrine_Query::create()
                    ->delete('Event')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('Bbs')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('BbsUnit')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('NewsCategory')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('Album')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('ClubMember')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('JoinApply')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('Banner')
                    ->where('club_id =?', $cid)
                    ->execute();
            Doctrine_Query::create()
                    ->delete('Club')
                    ->where('id =?', $cid)
                    ->execute();
        }
    }

}
