== PizazzWP ExcerptsPlus ==
Author: Chris Howard
Contributors: chrishoward
Tags: content,excerpts
Requires at least: 3.5.1
Tested up to: 4.6
Version: 3.4.14
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== DESCRIPTION ==
ExcerptsPlus is the Swiss Army Knife of content display, providing flexible and advanced content display. Adds a block that provides many more excerpt and content display options. Can be used to setup magazine layouts, featured post sliders, and even simple image galleries. In conjunction with custom posts types can create almost anything!

== INSTALLATION ==
- Download zip file but do not unzip it. If your computer unzips it, restore it from the trash/recycle bin.
- In WordPress admin, go to the Active Plugin list and deactivate the older version on Excerpts+
- In WordPress admin, go to the plugin installer and use the upload method, selecting the zip file you downloaded.
- Activate it when complete.

That's it! All should be well.

== SUPPORT & DOCUMENTATION: ==
If you are looking for detailed documentation, please visit http://guides.pizazzwp.com/excerptsplus/about-excerpts/
If you require support, please sen an email to support@pizazzwp.com or access the support form in WP Admin> PizazzWP > About & Support

Please review the changes listed below.
TODO:
fix Unknown column 'wp_postmeta.meta_value' in 'order clause'

Try this offset/pagination fix. http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
http://weblogtoolscollection.com/archives/2008/06/19/how-to-offsets-and-paging/
Or try a jquery solution.

== CHANGELOG ==

= 3.4.14 : =
* FIXED: Passing block ID in PadmaBlocksData::get_block_width was not working in HW4

= 3.4.13 : 21 March 2016 =
* ADDED: Option to format dates in custom fields
* ADDED: Option to show scheduled posts (useful for teasers). All post links are removed and no full content can be shown.
* ADDED: Class .ep-scheduled to scheduled posts
* FIXED: Problem with images on HTTPS
* FIXED: Error on get_property for border width and box shadow in HW4
* CHANGED: Alternate updater now uses EDD

= 3.4.12 : 8 Jan 2015 =
* FIXED: Pagination not working since WP 4.4 as WP made offset explicitly work as intended. https://core.trac.wordpress.org/ticket/34060

= 3.4.11 =
* CHANGED: Added a further message when source image can't be accessed.
* FIXED: PHP error when source image can't be opened by the image processor.

= 3.4.10 : 9 Nov 2015 =
* CHANGED: Custom fields dropdown sql query more specific
* CHANGED: Excluded categories now apply to blog index 
* FIXED: Alternate title had stopped returning the title

= 3.4.9 : 21 May 2015 = 
* CHANGED: Custom fields option now show some hidden fields, such as those in Woo Commerce
* FIXED: Images using home url instead of site url causing broken images on wp installs in sub directories. 
* CHANGED: When  using date range, it now is inclusive

= 3.4.8 : 5-Oct-2014 =
* FIXED: Fields that get hidden by certain settings, not showing as required
* FIXED: Messages when no results not being formatted to entry content styling
* FIXED: Content inline images not responsive

= 3.4.7 : 14-July-2014 =
* FIXED: Debug info appearing on some sites.

= 3.4.6 : 19-June-2012 =
-----------
* CHANGED: Removed Visual Editor options callback code not functioning correctly in 3.7 yet. Some options will now be visible all the time.

= 3.4.5 : 1-June-2014 =
---------------
* FIXED: Error if custom field selected for display, but none chosen to show
* FIXED: Problem with Is Image in custom fields field now returning a string value for true/false
* FIXED: Bug where selecting a tag messed up results (as it was using the tags for both include and exclude!)
* FIXED: Bug with TGM check class missing error on plugin activation
* FIXED: Added check and warning if GD Image library is not installed
* FIXED: Padma 3.7 compatibility

= 3.4.4 : 8-April-2014 =
------------------
* ADDED: Option to custom fields to indicate field is an image, so it'\s value is then put in an img tag to display it.
* FIXED: Styling for custom field content not showing in Design Editor
* CHANGED: Moved tint colour to same section (Images) as tint opacity since it is directly connected with Image Behind
* CHANGED: Moved Pizazz libs to their own plugin for heaps easier management

= 3.4.3  9-Feb-2014 =
------------------
* ADDED: Option to exclude posts with specified tags. Does not work if a category is selected for inclusion. This may be a WP limitation.
* ADDED: Rounded corners option to images in VE Design Editor
* CHANGED: When WPML is active, will display featured image from the parent post if the translation doesn't have one.
* FIXED: %readmorestr% or part of showing in some excerpts
* FIXED: Performance issue caused by trying to load missing unused jQuery file!
* FIXED: Corners option not showing in VE Design Editor

= 3.4.2 : 27-Nov-2013 =
FIXED: Quickread not working when settings left on defaults
FIXED: Quickread not showing tinted background
FIXED: Quickread zapping SliderPlus on pages

= 3.4.1 : 14-Nov-2013 =
ADDED: Design Mode styling option for paragraphs in custom fields.
ADDED: ep_before_loop_start hook. Useful for displaying things like WP-PageNavi atthetop of the block.
ADDED: Options to change meta text for comments

CHANGED: Major reorganisation of Visual Editor settings to improve workflow and usability. See here for overview of changes: http://guides.pizazzwp.com/excerptsplus/excerptsplus-v3-4-tab-layout-changes/

FIXED: Missing margins options from Design Mode
FIXED: A few minor notice messages
FIXED: Sort ordering not being applied to archive views.
FIXED: Slider not sliding

= 3.4.0 : 29-Oct-2013 =
ADDED: Option to override the "Full width content" to show as excerpts.
ADDED: Option to 'Always show read more text'
ADDED: A class .ep-any-sticky-post that will apply to any sticky post, regardless of the "Stickies First" setting.
ADDED: WP-Updates support for non-Padma sites

CHANGED: Using new date query methods for date filtering.
CHANGED: Image processing memory limit now WP_MAX_MEMORY_LIMIT to match WordPress.
CHANGED: Switched to WP's function for creating file paths for creating the cache path.
CHANGED: Order of tabs on Visual Editor
CHANGED: Now using wp_trim_words for trimming excerpt by words.
CHANGED: Removed WP post fields from custom fields drop down
CHANGED: Image widths when image behind to improve responsive behaviour. Images should now maintain 100% height, rather than 100% width.
This is to stop the gap opening between text and images but does mean images will crop on smaller screens and will not fill cell width if smaller than the cell width if the cell width changes to larger, eg. 25% to 100%.
CHANGED: jQuery Cycle naming to cycle1 to remove conflict with Cycle2 plugins

FIXED: Missing line breaks in WYSIWYG custom fields
FIXED: Caption to show when image is in the title
FIXED: Caption not showing for featured images that weren't originally attached to the current post.
FIXED: Some sites add gumpf after the file extension, which was causing cache images to not be created as E+ couldn't recognize the extension.
FIXED: Custom field showing groups 1 for all groups
FIXED: Focal point not working with attached/embedded images
FIXED: Strange alignments with entry titles when padding or margins added in Design Mode. Something had made the linked titles inline instead of inline-block.
FIXED: No content showing from pre-3.1.1 E+ blocks displaying custom post types
FIXED: Custom fields showing unformatted



See epblockchangelog.txt for full changelog