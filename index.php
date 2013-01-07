<?php 
//error_reporting(E_ALL);
/********************/
//function that calculates the time it takes to produce the page
/********************/
session_start();
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 
/*********(end)***********/

require_once  dirname(__FILE__).'/config/config.php';
function __autoload($class_name)
{
 require_once SITE_DIR . "classes/ver1/".$class_name.".php";
}
$Query = new Query( true);
$ContentLoader = new ContentLoader($_SERVER['REQUEST_URI'], true);
$Adminuser1 = new AdminUsers();
if($_GET['act']=="Logout"){
$Adminuser1->Logout();	
}
if($ContentLoader->members=="0" || ($ContentLoader->members=="1" && $Adminuser1->isLoggedIn() )){
//include the plugin file that process the content
include SITE_DIR."plugins/".$ContentLoader->process_file."/main.php";
}
else{
include SITE_DIR."plugins/login/main.php";	
}
?>
<?
//include the skin file that creates the <head> part of the page
include SITE_DIR."skins/".$ContentLoader->site_skin."/head.php";
?>
<body id="<?=$ContentLoader->page_id;?>" class="<?=$ContentLoader->language_name;?>">
<div id="wrapper1">
<?
//include the skin file that creates the header area
//include SITE_DIR."skins/".$ContentLoader->site_skin."/header.php";
?>
    <div id="content">
	<?=$content_body?>
   </div><!--content-->
<?
//include the skin file that creates the footer area
//include SITE_DIR."skins/".$ContentLoader->site_skin."/footer.php";
?>
</div><!--wrapper1-->
<?
/********************/
//function that calculates the time it takes to produce the page
/********************/
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 
echo "<!--<div style='position:absolute;bottom:0;'>This page was created in ".$totaltime." seconds</div>-->"; 
/*********(end)***********/
?> 	
</body>
</html>
