<?php
class GenFunctions{
    
    public static function check_valid_pw($password)
    {
            if(strlen($password)<8){return 0;}
            else{
                    $strength = 0;
                    $patterns = array('/[a-z]/','/[0-9]/','/[?-?]/','/[A-Z]/','/[0-9]/','/[�!"�$%^&*()`{}\[\]:@~;\'#<>?,.\/\\-=_+\|]/');
                    foreach($patterns as $pattern)
                    {
                            if(preg_match($pattern,$password)) {
                                    $strength++;
                            }
                    }
                    return $strength;
            }
    }
    
    public static function randomkeys($length)
    {
            $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
            for($i=0;$i<$length;$i++)
            {
                    $key .= $pattern{rand(0,35)};
            }
            return $key;
    }
    public static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
	public static function real_redirect($uri){
		
	header ("Location: $uri");	
	}
	
}
?>