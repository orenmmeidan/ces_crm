<?
ob_start();
$files_arr_js = array("functions.js");
$files_arr_css = array("style.css");
$jquery_plugin_arr = array(
							array("include/jquery/"),
							array("fancybox/jquery.mousewheel-3.0.4.pack.js","fancybox/jquery.fancybox-1.3.4.pack.js","fancybox/fancy_init.js"),
							array("fancybox/jquery.fancybox-1.3.4.css")
							);
?>
<div class='main_col'>
	<div class='header'><h1><?=$ContentLoader->content_loaded['header'];?></h1></div>
    <div class='main_col_left full_width'>
    <?=$ContentLoader->content_loaded['body']?>
   </div><!--main_column_left-->
   
</div><!--main_column-->
<div class='right_col'>
 
</div><!--right_column-->
<?	
$content_body = ob_get_contents();
ob_end_clean();  
?>