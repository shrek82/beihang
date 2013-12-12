<?php

class Controller_User_Point extends Layout_User {

    function action_index() {

        $div_point = Common_Point::divPoint();

        $my_point = $this->_user['point'];
        $view['my_point'] = $my_point;

        //排名
        $view['my_temp'] = Common_Point::getTemp($div_point, $my_point);
        $view['my_point_top'] = Common_Point::getTop($my_point);

        //紧跟的
        $view['previous'] = Doctrine_Query::create()
                        ->select('id,point,realname,sex')
                        ->from('User')
                        ->where('point>?', $my_point)
                        ->orderBy('point ASC')
                        ->limit(1)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        //追赶的
        $view['back'] = Doctrine_Query::create()
                        ->select('id,point,realname,sex')
                        ->from('User')
                        ->where('point<?', $my_point)
                        ->orderBy('point DESC')
                        ->limit(1)
                        ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        if ($this->_id == $this->_user_id){
            $view['who']='我';
            }
            elseif ( $this->_user['sex']=='男') {
            $view['who'] = '他';
        } else {
            $view['who'] = '她';
        }


        $this->_render('_body', $view);
    }

}