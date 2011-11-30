<?php

if (!defined('IN_CMS')) { exit(); }

$kla = Plugin::getSetting('folder', 'basic_gallery');
$wid = Plugin::getSetting('thumb_width', 'basic_gallery');
$hei = Plugin::getSetting('thumb_height', 'basic_gallery');
?>
<style>
	#fotolar li{ width: <?php echo $wid + 6?>px; height: <?php echo $hei + 65?>px; }
	#fotolar li a{ height: <?php echo $hei + 6?>px; }
</style>

<script type="text/javascript" language="javascript">
	$(document).ready(function(){
		$( "#fotolar" ).sortable({ 
			handle: '.tut',
			update: function(){
				$('#sir').val($('#fotolar').sortable('serialize'));
			}
		});
		$('#sir').val($('#fotolar').sortable('serialize'));
	});

	function kayitcek(id){
		$('#kayit').show();
		location.hash = 'kyt';
		$("#ajaxform").html("<p><?php echo __('Loading, please wait...');?></p>");
		$.ajax({
			type: "POST",
			url: '<?php echo get_url('plugin/basic_gallery/photo_record'); ?>',
			data: 'id=' + id + '&gal=' + <?php echo $gal?>,
			dataType: "html",
			success: function(sonuc){
				$("#ajaxform").html(sonuc);
			location.hash = 'kyt';
			}
		});
	}
</script>

<h1><?php echo __('Photos'); ?></h1>

<p><form action="<?php echo get_url('plugin/basic_gallery/photos'); ?>" method="get">
<?php echo __('Gallery');?> : <select name="gal" id="gal" class="textbox" onchange="location='<?php echo get_url('plugin/basic_gallery/photos?gal='); ?>' + $(this).val();">
		<option value="0"<?php if($gal == 0) echo ' selected="selected"';?>><?php echo __('None'); ?></option>
		<?php foreach($kytb as $k){?>
			<option value="<?php echo $k->id() ?>"<?php if($gal == $k->id()) echo ' selected="selected"';?>><?php echo $k->title() ?></option>
			<?php
		}?>
</select>
&nbsp; &nbsp; &nbsp;
<?echo __('to show this gallery on any page of frontend, use:');?> &nbsp; <span style="font-style: italic;">echo get_bg(<?php echo $gal?>);</span>
</form></p>


<p><a href="javascript: void(0);" onclick="kayitcek(0);" class="ac"><img src="<?php echo ICONS_URI;?>add-16.png" width="16" height="16" border="0" alt="<?php echo __('New Photo'); ?>" align="absmiddle" /><?php echo __('New Photo'); ?></a></p>

<?php
if($toplam){?>
	<form action="<?php echo get_url('plugin/basic_gallery/photo_ok'); ?>" method="post" id="tayfunduran">
	<ul id="fotolar">
		<?php $say = 0;
		foreach($kyt as $k){?>
			<li id="foto_<?php echo $k['id']?>">
				<span class="tut"><?php echo __('DRAG TO ORDER');?></span>
				<a href="javascript: void(0);" onclick="kayitcek(<?php echo $k["id"] ?>);" class="ac">
					<img src="/public/images/<?php echo $kla . '/zz_' . $k["dosya"] ?>" border="0" />
				</a>
				<span class="diger">
					<input type="hidden" name="id<?php echo $say;?>" value="<?php echo $k["id"];?>"/>
					<input type="hidden" name="dosya<?php echo $say;?>" value="<?php echo $k["dosya"];?>"/>
					<label class="ana" for="ana<?php echo $say; ?>"><input type="radio" name="ana" id="ana<?php echo $say; ?>" value="<?php echo $k["id"];?>"<?php if($k['ana']) echo ' checked="checked"';?> /><?php echo __('Main');?></label>
					<label class="sil" for="sil<?php echo $say; ?>"><?php echo __('Delete');?><input type="checkbox" name="sil<?php echo $say; ?>" id="sil<?php echo $say; ?>" value="1" /></label>
					<span class="galeri"><?php echo $k["bisim"] ?></span>
					<span class="bilgi"><?php echo $k["bilgi"] ?><span>
				</span>
			</li>
			<?php $say++;
		}?>
	</ul>
	<p style="clear: both;">
		<input type="hidden" name="sir" id="sir" value="" />
		<input type="hidden" name="gal" value="<?php echo $gal?>" />
		<input type="hidden" value="<?php echo $say;?>" name="say" id="say" />
		<input type="submit" class="button" value="<?php echo __('Save Order and Delete Selected'); ?>" name="tayfun" id="tayfun" />
	</p>
	</form>

	<p><a href="javascript: void(0);" onclick="kayitcek(0);" class="ac"><img src="<?php echo ICONS_URI;?>add-16.png" width="16" height="16" border="0" alt="<?php echo __('New Photo'); ?>" align="absmiddle" /><?php echo __('New Photo'); ?></a></p>
	<?php
}?>

<a name="kyt"></a>
<div id="kayit" style="display: none; border-top: 1px solid #999; margin-top: 25px; width: 400px;">
	<h3><?php echo __('Photo Data');?></h3>
	<form action="<?php echo get_url('plugin/basic_gallery/photo_save'); ?>" method="post" id="tayfunduran2" style="display:inline;" enctype="multipart/form-data">
	<input type="hidden" name="gal" value="<?php echo $gal?>" />
	<div id="ajaxform"></div>
	</form>
</div>
