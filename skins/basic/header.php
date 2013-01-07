<?
ob_start();  
?>
<div id="header">
    <div id="logo">
            <a href="<?=SITE_URL?>" title="<?=$Query->GetSetting("site_title",$ContentLoader->language, false);?>">&nbsp;</a>
    </div><!--logo-->
    <div id="menu">
    
    </div><!--menu-->
</div><!--header-->
<?
$buffer = ob_get_contents();
ob_end_clean();  
echo $buffer; 
?>

