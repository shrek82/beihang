<?php

class Controller_Test extends Layout_Main {

    public function before() {
        parent::before();
    }

    function action_index() {

        //Candy::import('oracle');
        //$odb = new OracleDb('192.168.66.200', 1521, 'xiaoyou', 'buaanicxy', 'buaadb');
        // $data = $odb->findAll("select FELLOW_ID,XM,XB,RXRQ,SZCS,XY,XL,ZY,BYRQ,XH,CSRQ,XS,XW,XZ from XY_FELLOW where xm='杨口'");
        //echo Kohana::debug($data);
        //$data2 = $db->findAll('SELECT * FROM dept');
        //$db->exec("INSERT INTO DEPT (DEPTNO,DNAME,LOC) VALUES (35,'董事长','吉林')");

        $alumni =new Model_Alumni();
        $alumni->conn();
        $auth = $alumni->matchAlumni(array('realname' => '杨口', 'start_year' => '1999', 'finish' => '2002', 'speciality' => '计算机'));
        echo Kohana::debug($auth);
        
        $one=$alumni->getOne(array('alumni_id'=>'20120517_9751'));
        echo Kohana::debug($one);
    }

}
