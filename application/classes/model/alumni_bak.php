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
class Model_Alumni_bak {

    const MDB_PATH = 'static/upload/mdb/';

    static $up_rule = array(
        'Upload::valid' => array(),
        'Upload::not_empty' => array(),
        'Upload::type' => array('Upload::type' => array('mdb')),
        'Upload::size' => array('20M')
    );

    //根据专业信息获取档案匹配的数据
    public static function getOneAlumni($data) {
        $alumni_id = isset($data['alumni_id']) ? (int) ($data['alumni_id']) : null;
        $name = isset($data['realname']) ? trim($data['realname']) : null;
        $start_year = isset($data['start_year']) ? (int) $data['start_year'] : null;
        $graduation_year = isset($data['graduation_year']) ? (int) $data['graduation_year'] : null;
        $graduation_year = empty($graduation_year) && $start_year ? $start_year + 4 : null;
        $speciality = isset($data['speciality']) ? trim($data['speciality']) : null;
        $file_no = isset($data['file_no']) ? trim($data['file_no']) : null;

        //通过档案序号查找
        if ($alumni_id AND $name) {
            $files = Doctrine_Query::create()
                    ->from('Alumni')
                    ->where('id=?', $alumni_id)
                    ->addWhere('name=?', $name)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }
        //通过档案编号和姓名
        elseif ($file_no AND $name) {
            $files = Doctrine_Query::create()
                    ->from('Alumni')
                    ->where('file_no=?', $file_no)
                    ->addWhere('name=?', $name)
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        }
        //通过精确查找
        elseif ($name AND $start_year AND $speciality) {
            $files = Doctrine_Query::create()
                    ->select('a.*')
                    ->from('Alumni a')
                    ->where('a.name=?', $name)
                    ->addWhere('(a.begin_year= ? OR a.graduation_year=?)', array($start_year, $start_year))
                    ->addWhere('a.speciality LIKE ?', '%' . trim($speciality) . '%')
                    ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        } else {
            $files = array();
        }

        return $files;
    }

}

?>
