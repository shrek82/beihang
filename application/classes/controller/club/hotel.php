<?php
//酒店俱乐部
class Controller_Club_Hotel extends Layout_Main {

    function action_index() {
         $ip= $this->getip();
         //校内ip
         if(preg_match("/^10\.10\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)){
             $this->_redirect('http://10.10.6.240/hotelClub');
         }
         //校外ip
         else{
             $this->_redirect('http://210.32.156.240/hotelClub');
         }
    }

    function getip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
       $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
       $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
       $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
       $ip = $_SERVER['REMOTE_ADDR'];
   else
       $ip = "unknown";
   return($ip);
}

}

?>
