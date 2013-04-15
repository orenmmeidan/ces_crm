<?
require_once  '../../../config/config.php';
function __autoload($class_name)
{
 require_once SITE_DIR . "classes/ver1/".$class_name.".php";
}
require_once "../classes/Crm.php";
$AdminUsers1 = new AdminUsers(true);
	
	
	//get the json object from the ajax file
	$json = $_POST['json'];
	$action = $_GET['action'];
	//decode the json object
    $content_arr = json_decode($json);
	
	if($action=="call_user_form"){
		$UserArr = 	$AdminUsers1->GetUserFullDetails($content_arr);
		$content_arr = json_encode($UserArr);
		echo $content_arr;
	}
	else if($action=="crate_new_user_form"){
		$UserArr = 	$AdminUsers1->CreateUserForm("", "");
		$content_arr = json_encode($UserArr);
		echo $content_arr;
	}
	
	
   
	
	
	

?>