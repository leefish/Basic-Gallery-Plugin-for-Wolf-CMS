== WHAT IT IS ==

Basic Gallery plugin is a gallery plugin which view the photos under the galleries
or sections. These sections are  Wolf CMS pages. When plugin is enabled
the main gallery page is selected. The children of this page are sections
of the Basic Gallery.

== HOW TO USE IT ==

* To use the settings and documentation pages, you will first need to enable the
  plugin!
* After enable the plugin, you must create a page and set the Page Type as Basic Gallery.
  This page is your main gallery. Children page of this page are galleries to which
  photos will be uploaded.
* From the Settings section, gallery page, upload folder (you must create that folder first), 
  title option for thumbnails, breadcrumb character of the gallery pages and thumbnail
  and photo dimentions can be set.
* On photos page, photos can be viewed as the thumbnails in the galleries, sorted by 
  drag and drop, deleted and edited. Default gallery of the photos is None, when the 
  photos page opened. Galleries can be choosen from the combo box from the top of the 
  page.
* On the frontend, selected main gallery page shows the sub galleries with their names
  and main photos of them. When clicked the gallery, their photo set is viewed. Colorbox
  (http://jacklmoore.com/colorbox/) was used to view the big photos.
* In addition to gallery pages on the frontend, galleries can be viewed on any pages by the function
		echo get_bg(id);
  id is the gallery id number which is showed on the photos pages. Main gallery page may
  be set as hidden and the sub galleries can be viewed on any page as well.

== NOTES ==

* When thumbnail or/and image sizes are changed, after upload some photo, uploaded images
  will not be resized!

== LICENSE ==
This plugin is licensed under the GPLv3 License. http://www.gnu.org/licenses/gpl.html
