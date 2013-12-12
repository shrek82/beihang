<?php

/**
  +-----------------------------------------------------------------
 * 校友网继承mysql档案库
 * 本地集成的档案数据库
 * 注意当给定alumni_id查找原档案信息时，需修改原档案库对应字段名称,有可能是id,有可能是file_no
  +-----------------------------------------------------------------
 */
class Model_Alumni {

    public $db;

    const fields = 'id,name,sex,speciality,begin_year,graduation_year,file_no,student_no,institute,institute_no,education,school,mobile,birthday,native_place';

    public function conn() {
        //$this->db = Database::instance();
        $this->db = Database::instance('alumni');
    }

    //匹配档案数据
    public function matchAlumni($post) {
        //兼容旧的接口
        $realname = Arr::get($post, 'realname');
        $name = Arr::get($post, 'name', $realname);
        $start = Arr::get($post, 'start_year');
        $graduation_year = Arr::get($post, 'graduation_year');
        $finish = Arr::get($post, 'finish_year', $graduation_year);
        $speciality = Arr::get($post, 'speciality');

        //条件递减的SQL
        if ($name AND $speciality AND $finish OR $start) {
            $query[] = "select  " . self::fields . "  from alumni where name='" . $name . "' AND speciality LIKE '%" . $speciality . "%' AND graduation_year = " . $finish . " AND begin_year = " . $start;
        }
        if ($name AND $speciality AND $finish) {
            $query[] = "select  " . self::fields . "  from alumni where name='" . $name . "' AND speciality LIKE '%" . $speciality . "%' AND graduation_year = " . $finish . " limit=20";
        }
        if ($name AND $speciality AND $start) {
            $query[] = "select  " . self::fields . "  from alumni where name='" . $name . "' AND speciality LIKE '%" . $speciality . "%' AND begin_year = " . $start . " limit=20";
        }
        if ($name AND $speciality) {
            $query[] = "select  " . self::fields . "  from alumni where name='" . $name . "' AND speciality LIKE '%" . $speciality . "%' limit=20";
        }
        if ($name AND $finish) {
            $query[] = "select  " . self::fields . "  from alumni where name='" . $name . "' AND graduation_year = " . $finish . " limit=20 ";
        }
        if ($name AND $start) {
            $query[] = "select  " . self::fields . "  from alumni where name='" . $name . "' AND begin_year = " . $start . " limit=20 ";
        }

        $query[] = "select  " . self::fields . "  from alumni where name='" . $name . "' limit=30";

        //条件依次递减
        $files = array();
        foreach ($query as $sql) {
            $files = $this->db->findAll($sql);
            if ($files) {
                break;
            }
        }

        //查询每隔档案信息是否已经被注册
        if ($files) {
            foreach ($files as $key => $f) {
                $files[$key]['is_reged'] = (boolean) DB::select('id')->from('user')->where('alumni_id', '=', $f['id'])->execute()->count();
            }
        }

        //修正字段名称
        $files = $this->oldToNewField($files);

        //返回查询结果
        return array('resp' => $files);
    }

    //转换为系统识别的字段名称
    public function oldToNewField($files) {
        //多条记录
        if (isset($files[0]['id'])) {
            foreach ($files as $key => $f) {
                $files[$key] = $this->addNewField($f);
            }
        }
        //单条记录
        elseif (isset($files['id'])) {
            $f = $files;
            $files = $this->addNewField($files);
        }
        return $files;
    }

    //添加新字段值
    public function addNewField($f) {
        $file['id'] = $f['id'] ? $f['id'] : null;
        $file['name'] = $f['name'] ? $f['name'] : null;
        $file['speciality'] = $f['speciality'] ? $f['speciality'] : null;
        $file['begin_year'] = $f['begin_year'] && strlen($f['begin_year']) > 4 ? substr($f['begin_year'], 0, 4) : null;
        $file['graduation_year'] = $f['graduation_year'] && strlen($f['graduation_year']) > 4 ? substr($f['graduation_year'], 0, 4) : null;
        $file['file_no'] = $f['file_no'] ? $f['file_no'] : null;
        $file['student_no'] = $f['student_no'] ? $f['student_no'] : null;
        $file['sex'] = $f['sex'] ? $f['sex'] : null;
        $file['institute'] = $f['institute'] ? $f['institute'] : null;
        $file['institute_no'] = null;
        $file['education'] = $f['education'] ? $f['education'] : null;
        $file['mobile'] = isset($f['mobile']) && $f['mobile'] ? $f['mobile'] : null;
        $file['mobile'] = isset($f['mobile']) && $f['mobile'] ? $f['mobile'] : $file['mobile'];
        $file['school'] = null;
        $file['birthday'] = isset($f['birthday']) && $f['birthday'] ? $f['birthday'] : null;
        $file['native_place'] = isset($f['native_place']) ? $f['native_place'] : null;
        $file['is_reged'] = isset($f['is_reged']) ? $f['is_reged'] : false;
        return $file;
    }

    //精确查询多条
    public function get($data) {
        $alumni_id = isset($data['id']) ? trim($data['id']) : null;
        $alumni_id = isset($data['alumni_id']) ? trim($data['alumni_id']) : $alumni_id;
        $name = isset($data['realname']) ? trim($data['realname']) : null;
        $start_year = isset($data['start_year']) ? (int) $data['start_year'] : null;
        $graduation_year = isset($data['graduation_year']) ? (int) $data['graduation_year'] : null;
        $graduation_year = empty($graduation_year) && $start_year ? $start_year + 4 : null;
        $speciality = isset($data['speciality']) ? trim($data['speciality']) : null;
        $file_no = isset($data['file_no']) ? trim($data['file_no']) : null;

        //通过档案序号查找
        if ($name AND $start_year AND $speciality) {
            $sql = "select  " . self::fields . "  from alumni where name='" . $name . "' AND speciality LIKE '%" . $speciality . "%' AND begin_year =" . $start_year . " limit=30";
        } elseif ($name AND $start_year) {
            $sql = "select  " . self::fields . "  from alumni where name='" . $name . "' AND begin_year =" . $start_year . " limit=30";
        } elseif ($name AND $speciality) {
            $sql = "select  " . self::fields . "  from alumni where name='" . $name . "' AND speciality LIKE '%" . $speciality . "%' limit=30";
        } elseif ($alumni_id) {
            $sql = "select  " . self::fields . "  from alumni where id='" . $alumni_id . "'";
        } elseif ($file_no) {
            $sql = "select  " . self::fields . "  from alumni where file_no='" . $file_no . "'";
        } elseif ($name) {
            $sql = "select  " . self::fields . "  from alumni where name='" . $name . "' limit=30";
        }
        //通过精确查找
        else {
            return array();
        }

        $alumni = $this->db->findAll($sql);
        $files = $this->oldToNewField($alumni);

        return $files;
    }

    //精确查询一条档案信息
    public function getOne($data) {
        $alumni_id = isset($data['id']) ? trim($data['id']) : null;
        $alumni_id = isset($data['alumni_id']) ? trim($data['alumni_id']) : $alumni_id;
        $name = isset($data['realname']) ? trim($data['realname']) : null;
        $start_year = isset($data['start_year']) ? (int) $data['start_year'] : null;
        $graduation_year = isset($data['graduation_year']) ? (int) $data['graduation_year'] : null;
        $graduation_year = empty($graduation_year) && $start_year ? $start_year + 4 : null;
        $speciality = isset($data['speciality']) ? trim($data['speciality']) : null;
        $file_no = isset($data['file_no']) ? trim($data['file_no']) : null;

        //通过档案序号查找
        if ($name AND $start_year AND $speciality) {
            $sql = "select  " . self::fields . "  from alumni where name='" . $name . "' AND speciality LIKE '%" . $speciality . "%' AND begin_year =" . $start_year;
        } elseif ($alumni_id) {
            $sql = "select  " . self::fields . "  from alumni where id='" . $alumni_id . "'";
        } elseif ($file_no) {
            $sql = "select  " . self::fields . "  from alumni where file_no='" . $file_no . "'";
        } elseif ($name) {
            $sql = "select  " . self::fields . "  from alumni where name='" . $name . "' limit=2";
        }
        //通过精确查找
        else {
            return array();
        }

        $alumni = $this->db->findOne($sql);
        $files = $this->oldToNewField($alumni);

        return $files;
    }

}
