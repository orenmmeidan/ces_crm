<?
ob_start();  
?>
<div id="footer">
<div id="copyrights">
<p>
<?=$Query->GetSetting("footer_credit",$ContentLoader->language, false);?>
</p>
</div>
<div id="credits">
<p>
<a href="http://www.stotlandesigns.com">web design &amp; web development</a> by <strong>stotlandesigns</strong>
</p>
</div>
</div><!--footer-->
<?
$buffer = ob_get_contents();
ob_end_clean();  
echo $buffer; 
?>

