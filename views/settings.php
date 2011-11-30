<?php

if (!defined('IN_CMS')) { exit(); }

function cek($s, $v, $i){
	$i++;
	$p = Page::findById($s);
	if(Page::hasChildren($p->id())){
		foreach($p->children(null, array(), true) as $m){?>
			<option value="<?php echo $m->id()?>"<?php if($v == $m->id()) echo ' selected="selected"';?>>
				<?php echo str_repeat('-', $i) . ' ' . $m->title()?>
			</option>
			<?php
			cek($m->id(), $v, $i);
		}
	}
}
?>

<h1><?php echo __('Settings'); ?></h1>

<?php
if(AuthUser::hasRole('administrator')){?>

	<form action="<?php echo get_url('plugin/basic_gallery/save'); ?>" method="post">
		<fieldset style="padding: 0.5em;">
			<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('General settings'); ?></legend>
			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="label"><label for="setting_slug"><?php echo __('Gallery Page'); ?>: </label></td>
					<td class="field">
						<select class="textbox" id="setting_slug" style="width: 120px;" name="settings[page]">
						<?php cek(1, $settings['page'], 0);?>
						</select>
					</td>
					<td class="help"><?php echo __('Select the gallery page in the CMS. Create one first and <u> set Page Type as Basic Gallery</u>'); ?></td>
				</tr>
				<tr>
					<td class="label"><label for="setting_folder"><?php echo __('Folder'); ?>: </label></td>
					<td class="field"><span style="font-size:10px;">/public/images/</span><input class="textbox" id="setting_folder" style="width: 120px;" name="settings[folder]" type="text" value="<?php echo $settings['folder']; ?>" /></td>
					<td class="help"><?php echo __('Folder name under the /public/images folder where photos uploaded. eg. photos'); ?></td>
				</tr>
				<tr>
					<td class="label"><label for="setting_title"><?php echo __('Show titles'); ?>: </label></td>
					<td class="field">
						<label for="setting_title1"><input id="setting_title1" name="settings[title]" type="radio" value="1"<?php if($settings['title']) echo ' checked="checked"';?> /> <?php echo __('Yes');?></label>
						&nbsp; &nbsp;
						<label for="setting_title0"><input id="setting_title0" name="settings[title]" type="radio" value="0"<?php if(!$settings['title']) echo ' checked="checked"';?> /> <?php echo __('No');?></label>
					</td>
					<td class="help"><?php echo __('Show titles under the thumbnails on galleries.'); ?></td>
				</tr>
				<tr>
					<td class="label"><label for="setting_bcsep"><?php echo __('Breadcrumb Sep.'); ?>: </label></td>
					<td class="field"><input class="textbox" id="setting_bcsep" style="width: 60px;" name="settings[bcsep]" type="text" value="<?php echo $settings['bcsep']; ?>" /></td>
					<td class="help"><?php echo __('Breadcrumb separator which used on the gallery pages.'); ?></td>
				</tr>
			</table>
		</fieldset>

		<fieldset style="padding: 0.5em;">
			<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Thumbnail settings'); ?></legend>
			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="label"><label for="setting_thumb_width"><?php echo __('Width'); ?>: </label></td>
					<td class="field"><input class="textbox" id="setting_thumb_width" style="width: 60px; text-align: right;" name="settings[thumb_width]" type="text" value="<?php echo $settings['thumb_width']; ?>" />px.</td>
					<td class="help"><?php echo __('Maximum width of the thumbnail images.'); ?></td>
				</tr>
				<tr>
					<td class="label"><label for="setting_thumb_height"><?php echo __('Height'); ?>: </label></td>
					<td class="field"><input class="textbox" id="setting_thumb_height" style="width: 60px; text-align: right;" name="settings[thumb_height]" type="text" value="<?php echo $settings['thumb_height']; ?>" />px.</td>
					<td class="help"><?php echo __('Maximum height of the thumbnail images.'); ?></td>
				</tr>
			</table>
		</fieldset>

		<fieldset style="padding: 0.5em;">
			<legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Image settings'); ?></legend>
			<table class="fieldset" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="label"><label for="setting_width"><?php echo __('Width'); ?>: </label></td>
					<td class="field"><input class="textbox" id="setting_width" style="width: 60px; text-align: right;" name="settings[width]" type="text" value="<?php echo $settings['width']; ?>" />px.</td>
					<td class="help"><?php echo __('Maximum width of the images.'); ?></td>
				</tr>
				<tr>
					<td class="label"><label for="setting_height"><?php echo __('Height'); ?>: </label></td>
					<td class="field"><input class="textbox" id="setting_height" style="width: 60px; text-align: right;" name="settings[height]" type="text" value="<?php echo $settings['height']; ?>" />px.</td>
					<td class="help"><?php echo __('Maximum height of the images.'); ?></td>
				</tr>
			</table>
		</fieldset>

		<p class="buttons">
			<input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
		</p>
	</form>

	<?php
}else echo '<p>' . __('You are not authorized to view this page!') . '</p>';
