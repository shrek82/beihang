<?php

//苏州校友会档案数据库查询

class Db_Alumni {

    //添加新字段值
    public static function addNewField($file) {
        $f = $file;
        $file['id'] = $f['FELLOW_ID'] ? $f['FELLOW_ID'] : null;
        $file['name'] = $f['XM'] ? $f['XM'] : null;
        $file['speciality'] = $f['ZY'] ? $f['ZY'] : null;
        $file['begin_year'] = $f['RXRQ']&&strlen($f['RXRQ'])>4 ?substr($f['RXRQ'],0,4) : null;
        $file['graduation_year'] = $f['BYRQ']&&strlen($f['BYRQ'])>4 ?substr($f['BYRQ'],0,4) : null;
        $file['file_no'] = $f['FELLOW_ID'] ? $f['FELLOW_ID'] : null;
        $file['student_no'] = $f['XH'] ? $f['XH'] : null;
        $file['sex'] = $f['XB'] ? $f['XB'] : null;
        $file['institute'] = $f['XY'] ? $f['XY'] : null;
        $file['institute_no'] = null;
        $file['education'] = $f['XW'] ? $f['XW'] : null;
        $file['mobile']=isset($f['LXDH1'])&&$f['LXDH1'] ? $f['LXDH1'] : null;
        $file['mobile']=isset($f['LXDH31'])&&$f['LXDH3']? $f['LXDH3'] :$file['mobile'];
        $file['school'] = null;
        $file['birthday'] = isset($f['CSRQ'])&&$f['CSRQ'] ? $f['CSRQ'] : null;
        $file['native_place'] = isset($f['JG']) ? $f['JG'] : null;
        
        return $file;
    }

    //转换为系统识别的字段名称
    public static function oldToNewField($files) {
        //多条记录
        if (isset($files[0]['FELLOW_ID'])) {
            foreach ($files as $key => $f) {
                $files[$key] = self::addNewField($f);
            }
        }
        //单条记录
        elseif (isset($files['FELLOW_ID'])) {
            $f = $files;
            $files = self::addNewField($files);
        }
        return $files;
    }

}
