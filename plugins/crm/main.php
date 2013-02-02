<?php
require_once "classes/Crm.php";
$files_arr_js = array("js.js");
$CRM1 = new Crm(true);
$curr_tab = ($_GET['tab'])?$_GET['tab']:"";
$view_deleted= ($_POST['view_deleted'])?$_POST['view_deleted']:"1";
ob_start();
?>
<div id="tabs">
<div id="controlpanel">
	<input type="submit" id="save_cells" class="button" value="Save" />
	<div id="controlpanel_message">
	
	</div><!--#controlpanel_message-->
	<div id="controlpanel_user">
	<form id="dr_form" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="POST">
		<?php
if($_SESSION['user_type']==1){
	?>
	
	<select name="view_deleted">
		<option  value="1" <?php echo ($view_deleted=="1")?"selected":"";?>>live rows</option>
		<option  value="3" <?php echo ($view_deleted=="3")?"selected":"";?>>deleted rows</option>
		<option  value="2"<?php echo ($view_deleted=="2")?"selected":"";?>>all rows</option>
	</select>
	<?php
		}
?>
	</form>
	<form action="./" method="get">
		
		<?php echo $Adminuser1->GetUserDetail(fname);?>
	<input type="submit" id="logout" name="act" class="button" value="Logout" />	
	</form>
	
	</div><!--#controlpanel_user-->
</div><!--#controlpanel-->
<?
echo $CRM1->prepare_tabs($curr_tab);
?>
</div><!--#tabs-->


			
		<div id="DisplayUsersArea">
		<?php
		if($curr_tab=="users" && $_SESSION['user_type']==1)
			{
				$files_arr_js = array("users.js");
					
				echo $Adminuser1->DisplayUsers();
			}
			else
			{
				echo $CRM1->prepare_leads_table($curr_tab, $view_deleted);
			}
		?>
			</div><!--#DisplayUsersArea-->
			<div id="UserFormArea">
			<?php
			if($_POST['submit']){
				echo $Adminuser1->ProcessUser($_POST);
			}
			if($_POST['submit_edit_user']){
				echo $Adminuser1->ProcessEditUser($_POST);
			}
			?>
			</div>
		

<?php	
$content_body = ob_get_contents();
ob_end_clean();  
?>