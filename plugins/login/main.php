<?
if($_POST['submit'] ){
	$user=$_POST['user'];
	$pass=$_POST['pass'];
	$result = $Adminuser1->Login($user,$pass);
	if($result===TRUE){
	$uri = $_SERVER["REQUEST_URI"];
  	GenFunctions::real_redirect($uri);	
	}

}
$result = ($result)?"<p class='error'>$result</p>":"";
ob_start();
?>
<div id="login_div" class='form_div'>
<h2><em>Login</em></h2>
<?php echo $result;?> 
<form action="" method="post" >
	<label>Username:</label><input type="text" name="user" id="user" value="" /><br />
	<label>Password:</label><input type="password" name="pass" id="pass" value="" /><br />
	<input type="submit"  name="submit" id="submit" value="Submit" />
</form>

</div><!--#login_div-->



<?	
$content_body = ob_get_contents();
ob_end_clean();  
?>