<?php

/**
  +-----------------------------------------------------------------
 * 档案管理接口oracle
  +-----------------------------------------------------------------
 */
class Model_Alumni {

    public $db;

    const fields = 'FELLOW_ID,XM,XB,RXRQ,SZCS,XY,XL,ZY,BYRQ,XH,CSRQ,XS,XW,XZ,LXDH1,LXDH3,JG';
    const MDB_PATH = 'static/upload/mdb/';

    public function conn() {
        Candy::import('oracle');
        $this->db = new OracleDb('192.168.66.200', 1521, 'xiaoyou', 'buaanicxy', 'buaadb');
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
            $query[] = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ZY LIKE '%" . $speciality . "%' AND BYRQ LIKE '" . $finish . "%' AND RXRQ LIKE '" . $start . "%' ";
        }
        if ($name AND $speciality AND $finish) {
            $query[] = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ZY LIKE '%" . $speciality . "%' AND BYRQ LIKE '" . $finish . "%' AND ROWNUM<20";
        }
        if ($name AND $speciality AND $start) {
            $query[] = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ZY LIKE '%" . $speciality . "%' AND RXRQ LIKE '" . $start . "%' AND ROWNUM<20";
        }
        if ($name AND $speciality) {
            $query[] = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ZY LIKE '%" . $speciality . "%' AND ROWNUM<20 ";
        }
        if ($name AND $finish) {
            $query[] = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND BYRQ LIKE '" . $finish . "%' AND ROWNUM<20 ";
        }
        if ($name AND $start) {
            $query[] = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND RXRQ LIKE '" . $start . "%' AND ROWNUM<20 ";
        }

        $query[] = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ROWNUM<20";

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
                $files[$key]['is_reged'] = (boolean) DB::select('id')->from('user')->where('alumni_id', '=', $f['FELLOW_ID'])->execute()->count();
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
        if (isset($files[0]['FELLOW_ID'])) {
            foreach ($files as $key => $f) {
                $files[$key] = $this->addNewField($f);
            }
        }
        //单条记录
        elseif (isset($files['FELLOW_ID'])) {
            $f = $files;
            $files = $this->addNewField($files);
        }
        return $files;
    }

    //添加新字段值
    public function addNewField($f) {
        $file['id'] = $f['FELLOW_ID'] ? $f['FELLOW_ID'] : null;
        $file['name'] = $f['XM'] ? $f['XM'] : null;
        $file['speciality'] = $f['ZY'] ? $f['ZY'] : null;
        $file['begin_year'] = $f['RXRQ'] && strlen($f['RXRQ']) > 4 ? substr($f['RXRQ'], 0, 4) : null;
        $file['graduation_year'] = $f['BYRQ'] && strlen($f['BYRQ']) > 4 ? substr($f['BYRQ'], 0, 4) : null;
        $file['file_no'] = $f['FELLOW_ID'] ? $f['FELLOW_ID'] : null;
        $file['student_no'] = $f['XH'] ? $f['XH'] : null;
        $file['sex'] = $f['XB'] ? $f['XB'] : null;
        $file['institute'] = $f['XY'] ? $f['XY'] : null;
        $file['institute_no'] = null;
        $file['education'] = $f['XW'] ? $f['XW'] : null;
        $file['mobile'] = isset($f['LXDH1']) && $f['LXDH1'] ? $f['LXDH1'] : null;
        $file['mobile'] = isset($f['LXDH31']) && $f['LXDH3'] ? $f['LXDH3'] : $file['mobile'];
        $file['school'] = null;
        $file['birthday'] = isset($f['CSRQ']) && $f['CSRQ'] ? $f['CSRQ'] : null;
        $file['native_place'] = isset($f['JG']) ? $f['JG'] : null;
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
        $institute = isset($data['institute']) ? trim($data['institute']) : null;
        $speciality = isset($data['speciality']) ? trim($data['speciality']) : null;
        $file_no = isset($data['file_no']) ? trim($data['file_no']) : null;

        //通过档案序号查找
        if ($name AND $start_year AND $speciality) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ZY LIKE '%" . $speciality . "%' AND RXRQ LIKE '" . $start_year . "%' AND rownum<100";
        } elseif ($name AND $start_year) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND RXRQ LIKE '" . $start_year . "%' AND rownum<100";
        } elseif ($name AND $institute) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND XY LIKE '%" . $institute . "%' AND rownum<100";
        } elseif ($name AND $speciality) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ZY LIKE '%" . $speciality . "%' AND rownum<100";
        } elseif ($alumni_id) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where FELLOW_ID='" . $alumni_id . "'";
        } elseif ($file_no) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where FELLOW_ID='" . $file_no . "'";
        } elseif ($name) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND rownum<150";
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
            $sql = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND ZY LIKE '%" . $speciality . "%' AND RXRQ LIKE '" . $start_year . "%' ";
        } elseif ($alumni_id) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where FELLOW_ID='" . $alumni_id . "'";
        } elseif ($file_no) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where FELLOW_ID='" . $file_no . "'";
        } elseif ($name) {
            $sql = "select  " . self::fields . "  from XY_FELLOW where xm='" . $name . "' AND rownum<2";
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
