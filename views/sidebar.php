<?php

if (!defined('IN_CMS')) { exit(); }

?>
	<p class="button"><a href="<?php echo get_url('plugin/basic_gallery/photos'); ?>"><img src="<?php echo ICONS_URI;?>file-image-32.png" align="middle" alt="photo icon" /> <?php echo __('Photos'); ?></a></p>
	<p class="button"><a href="<?php echo get_url('plugin/basic_gallery/documentation'); ?>"><img src="<?php echo ICONS_URI;?>page-32.png" align="middle" alt="documentation icon" /> <?php echo __('Documentation'); ?></a></p>
	<?php if(AuthUser::hasRole('administrator')){?>
		<p class="button"><a href="<?php echo get_url('plugin/basic_gallery/settings'); ?>"><img src="<?php echo ICONS_URI;?>settings-32.png" align="middle" alt="settings icon" /> <?php echo __('Settings'); ?></a></p>
		<?php
	}?>

<div class="box">
	<h2><?php echo __('Basic Gallery Plugin');?></h2>
<?php echo '<p>' . __('Basic Gallery plugin is a gallery plugin which view the photos under the galleries
or sections. These sections are  Wolf CMS pages. When plugin is enabled
the main gallery page is selected. The children of this page are sections
of the Basic Gallery.') . '</p>';
?>
</div>
