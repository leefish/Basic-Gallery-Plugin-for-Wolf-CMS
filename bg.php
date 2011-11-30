<?php
$wid = $_GET['w'];
$hei = $_GET['h'];
$tit = $_GET['t'];
if($tit) $ek = $hei + 30; else $ek = $hei + 6;
header('Content-type: text/css');
?>

#basicgallery{ list-style: none; clear: both; margin: 0px; padding: 0;}
#basicgallery li{ float: left; display: block;  font-size: 11px;line-height: 11px; border: 1px solid #ccc; margin: 5px;  padding: 0;
	text-align: center; width: <?php echo $wid + 6?>px; height: <?php echo $ek?>px; 
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
#basicgallery li a{ display: block; margin: 3px auto 3px; height: <?php echo $hei + 6?>px; }
#basicgallery li .title{ display: block; }
#basicgallery li .title2{ display: block; font-size: 12px; font-weight: bold; }
