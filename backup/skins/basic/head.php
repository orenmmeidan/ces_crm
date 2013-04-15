<?
ob_start();  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="icon" href="<?=SITE_URL?>favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?=SITE_URL?>favicon.ico" />
<title><?=$ContentLoader->content_loaded['meta_title']?></title>
<meta name="description" content="<?=$ContentLoader->content_loaded['meta_description']?>" />
<meta name="keywords" content="<?=$ContentLoader->content_loaded['meta_keywords']?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?=SITE_URL ."skins/".$ContentLoader->site_skin."/css/layout.css"?>" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
<?=$ContentLoader->load_plugin_css_files($files_arr_css)?>
<?=$ContentLoader->load_plugin_js_files($files_arr_js)?>
<?=$ContentLoader->load_jquery_files($jquery_plugin_arr)?>
</head>
<?
$buffer = ob_get_contents();
ob_end_clean();  
echo $buffer; 
?>

