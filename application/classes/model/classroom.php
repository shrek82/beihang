<?php

/**
  +-----------------------------------------------------------------
 * 名称：班级模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */
class Model_Classroom {

    public static function addressbook($user_id) {
        return Doctrine_Query::create()
                        ->from('ClassAbook ca')
                        ->where('ca.user_id = ?', $user_id)
                        ->fetchOne(array());
    }

    # 激活一个班级

    public static function active($id) {
        $classroom = Doctrine_Query::create()
                ->from('ClassRoom')
                ->where('id = ?', $id)
                ->andWhere('create_at IS NULL')
                ->fetchOne();

        if ($classroom) {
            $classroom['create_at'] = date('Y-m-d H:i:s');
            $classroom->save();
            return true;
        }
    }

    //设置为管理员
    public static function setManager($id, $usre_id) {
        $member = Doctrine_Query::create()
                ->from('ClassMember')
                ->where('class_room_id = ?', $id)
                ->addWhere('user_id=?', $usre_id)
                ->fetchOne();

        if ($member) {
            $member['is_manager'] = True;
            $member->save();
        }
    }

    # 直接加入班
    public static function join($id, $user_id) {
        if (!self::isMember($id, $user_id)) {
            $is_new_one = self::active($id);
            $member = new ClassMember();
            $member->user_id = $user_id;
            $member->join_at = date('Y-m-d H:i:s');
            $member->visit_at = date('Y-m-d H:i:s');
            $member->class_room_id = $id;

            // 第一个成员自动成为管理员
            if ($is_new_one == true) {
                $member->is_manager = true;
            }

            $member->save();

            //更新班级总人数
            self::updateMemberNum($id);

            return $member->id;
        }
    }

    # 是否已经是成员

    public static function isMember($id, $user_id) {
        return (bool) Doctrine_Query::create()
                        ->from('ClassMember')
                        ->where('class_room_id = ?', $id)
                        ->andWhere('user_id = ?', $user_id)
                        ->count();
    }

    //查找某校友加入的班级
    public static function userjoinedClass($user_id) {
        $classroom = Doctrine_Query::create()
                ->select('cr.speciality AS speciality,cr.start_year AS start_year,cr.name AS name,cr.id AS class_room_id')
                ->from('ClassMember cm')
                ->leftJoin('cm.ClassRoom cr')
                ->where('cm.user_id = ?', $user_id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($classroom['speciality']) {
            return $classroom;
        }
    }

    //更新班级加入人数
    public static function updateMemberNum($class_room_id) {

        $classroom = Doctrine_Query::create()
                ->from('ClassRoom')
                ->where('id = ?', $class_room_id)
                ->fetchOne();

        $member_num = Doctrine_Query::create()
                ->from('ClassMember')
                ->where('class_room_id = ?', $class_room_id)
                ->count();

        $post['member_num'] = $member_num;
        $classroom->fromArray($post);
        $classroom->save();
        return $member_num;
    }

    //更新评论总数
    public static function updateBbsUnitCommentNum($class_unit_id) {
        $total_comments = Doctrine_Query::create()
                ->from('Comment')
                ->where('class_unit_id = ?', $class_unit_id)
                ->count();

        $class_bbs_unit = Doctrine_Query::create()
                ->from('ClassBbsUnit')
                ->where('id = ?', $class_unit_id)
                ->fetchOne();

        $class_bbs_post['reply_num'] = $total_comments;
        $class_bbs_post['comment_at'] = date('Y-m-d H:i:s');
        $class_bbs_unit->fromArray($class_bbs_post);
        $class_bbs_unit->save();
        return $total_comments;
    }

}