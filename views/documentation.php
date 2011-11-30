<?php

if (!defined('IN_CMS')) { exit(); }

?>
<h1><?php echo __('Documentation'); ?></h1>
<p>
<?php 
echo '<h2>' . __('What is Basic Gallery Plugin') . '</h2>';
echo '<p>' . __('Basic Gallery plugin is a gallery plugin which view the photos under the galleries
or sections. These sections are  Wolf CMS pages. When plugin is enabled
the main gallery page is selected. The children of this page are sections
of the Basic Gallery.') . '</p>';

echo '<h2>' . __('How to Use It') . '</h2>';
echo '<p><ul class="bilgi">';
echo '<li>' . __('To use the settings and documentation pages, you will first need to enable the plugin!') . '</li>';
echo '<li>' . __('After enable the plugin, you must create a page and set the Page Type as Basic Gallery.
  This page is your main gallery. Children page of this page are galleries to which
  photos will be uploaded.') . '</li>';
echo '<li>' . __('From the Settings section, gallery page, upload folder (you must create that folder first), 
  title option for thumbnails, breadcrumb character of the gallery pages and thumbnail
  and photo dimentions can be set.') . '</li>';
echo '<li>' . __('On photos page, photos can be viewed as the thumbnails in the galleries, sorted by 
  drag and drop, deleted and edited. Default gallery of the photos is None, when the 
  photos page opened. Galleries can be choosen from the combo box from the top of the 
  page.') . '</li>';
echo '<li>' . __('On the frontend, selected main gallery page shows the sub galleries with their names
  and main photos of them. When clicked the gallery, their photo set is viewed. Colorbox
  (http://jacklmoore.com/colorbox/) was used to view the big photos.') . '</li>';
echo '<li>' . __('In addition to gallery pages on the frontend, galleries can be viewed on any pages by the function') . '<br />';
echo '&nbsp; &nbsp; <i>if (Plugin::isEnabled(\'basic_gallery\')) echo get_bg(id);</i>';
echo '<br />' . __('id is the gallery id number which is showed on the photos pages. Main gallery page may
  be set as hidden and the sub galleries can be viewed on any page as well.') . '</li></ul>';

echo '<h2>' . __('Notes') . '</h2>';
echo '<p>' . __('When thumbnail or/and image sizes are changed, after upload some photo, uploaded images
  will not be resized!') . '</p>';

echo '<h2>' . __('License') . '</h2>';
echo '<p>' . __('This plugin is licensed under the GPLv3 License.') . ' http://www.gnu.org/licenses/gpl.html' . '</p>';

