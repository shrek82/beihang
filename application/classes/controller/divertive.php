<?php

//一些有趣的东东
class Controller_Divertive extends Layout_Main {
    
    //惟愿诸君将振兴中华之责任，置之于自身之肩上

    //360谚语
    function action_proverb() {
        $this->auto_render=false;
        $data = file_get_contents('http://www.so.com/');
        preg_match_all('/<div id="slogan"><a.*?>(.*?)<\/a><\/div>/is',$data,$array);//提取邮编等信息

        if(isset($array[1][0]) && $array[1][0]){
            echo $array[1][0];
        }
    }

}