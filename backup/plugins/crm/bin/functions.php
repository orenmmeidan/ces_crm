<?
session_start();
require_once  '../../../config/config.php';
function __autoload($class_name)
{
 require_once SITE_DIR . "classes/ver1/".$class_name.".php";
}
require_once "../classes/Crm.php";
$CRM1 = new Crm(true);
	
	
	//get the json object from the ajax file
	$json = $_POST['json'];
	$action = $_GET['action'];
	//decode the json object
    $content_arr = json_decode($json);

        if ($action=='load'){
           $tab=$_GET['tab'];
           $view_deleted=$_GET['view_deleted'];
           $offset = $_GET['offset'];
           $limit = $_GET['limit'];
           $filter=$CRM1->get_filter( $tab, $view_deleted );
           if ($CRM1->get_table()=='leads')  $rows = $CRM1->prepare_for_display_result_set($filter,$view_deleted, $offset, $limit);
           if ($CRM1->get_table()=='clients') $rows=$CRM1->prepare_for_display_clients_result_set($filter, $limit, $offset);

           if ($CRM1->get_table()=='expiration')  $rows=$CRM1->prepare_for_display_expiration_result_set($filter, $limit, $offset);

             echo $rows;
        }
	
	if($action=="update"){
	//echo "update";		
	echo  $CRM1->update_leads_table($content_arr);
	
	}
	elseif($action=="insert"){
	$result_arr = 	$CRM1->insert_row_to_table_table($content_arr);
	$content_arr = json_encode($result_arr);
	echo $content_arr;
	}
	elseif($action=="delete"){
	$result_arr = 	$CRM1->delete_row($content_arr);
	$content_arr = json_encode($result_arr);
	echo $content_arr;
	}
	elseif($action=="restore"){
	$result_arr = 	$CRM1->restore_row($content_arr);
	$content_arr = json_encode($result_arr);
	echo $content_arr;
	}
	
   
	
	
	

?>