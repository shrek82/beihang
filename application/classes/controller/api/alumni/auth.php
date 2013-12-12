<?php

header("content-type:text/html; charset=utf-8");

class Controller_Api_Alumni_Auth extends Controller_REST {

    /**
     * 姓名，专业，入学或毕业年份列出对应结果
     */
    function action_index() {
        $name = Arr::get($_GET, 'realname');
        $start = Arr::get($_GET, 'start_year');
        $finish = Arr::get($_GET, 'graduation_year');
        $depart = Arr::get($_GET, 'depart');
        //$birthday = Arr::get($_GET, 'birthday');

        $r = Doctrine_Query::create()
                ->select('a.id,a.name,a.begin_year,a.graduation_year,a.school,a.institute,a.speciality,a.education')
                ->addSelect('(SELECT u.alumni_id FROM User u WHERE u.alumni_id = a.id limit 1) AS is_reged')
                ->from('Alumni a')
                ->where('a.name = ?', $name)
                ->andWhere('a.begin_year = ? OR a.graduation_year = ?', array($start, $finish))
                ->andWhere('a.speciality = ?', $depart);

        if ($r->count() == 0) {
            // 条件减法
            $r->removeDqlQueryPart('where');
            $r->where('a.name = ?', $name);
            if ($r->count() > 0) {
                $r->andWhere('a.begin_year = ? OR a.graduation_year = ?', array($start, $finish));
            }
        }

        echo json_encode(array('resp' => $r->fetchArray()));
    }

}