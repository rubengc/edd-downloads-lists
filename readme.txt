=== EDD Downloads Lists ===
Contributors: rubengc
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=64N6CERD8LPZN
Tags: easy digital downloads, digital, download, downloads, edd, rubengc, list, lists, wish, like, favorite, recommend, wishes, likes, favorites, recommendations, recommendation, widget, widgets, e-commerce
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds custom lists to EDD Wish Lists

== Description ==
This plugin requires [Easy Digital Downloads](http://wordpress.org/extend/plugins/easy-digital-downloads/ "Easy Digital Downloads") and [EDD Wish Lists](https://easydigitaldownloads.com/downloads/edd-wish-lists/).

Once activated, EDD Downloads Lists will allow you add a separated user downloads list.

The idea of this plugin is keep EDD Wish List as a user lists manager and EDD Downloads Lists as internal list manager.

EDD Downloads Lists has four predefined lists:

1. Wishes
1. Favorites
1. Likes
1. Recommendations

You can enable any list at any moment. When you enable a list, you will be able to set this settings for each list:

1. Label: Defines the button label
1. Icon: Defines the button icon
1. Count: Show/Hide total times this download has been added to this type of lists
1. Style: Defines the button style (Plain text or button)
1. Page: Defines the page where users can see their list (The page needs the shortcode [edd_downloads_lists])
1. Guest: Allow guest to create this type of lists
1. Cart: Show/Hide an add to cart button on this type of lists
1. Share: Show/Hide share options on this type of lists

Also includes a widget to render enabled list buttons on Download's sidebar.

For developers, read the FAQ section to learn how to add or modify default lists.

There's a [GIT repository](https://github.com/rubengc/edd-downloads-lists) too if you want to contribute a patch.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin
1. That's it!

OR you can just install it with WordPress by going to Plugins >> Add New >> and type this plugin's name

== Frequently Asked Questions ==

= How can I add my own list?  =

You can add your own lists using the filter `edd_downloads_lists_registered_lists`.

This is the structure for a list:

``
$list = array(
    'list_identifier' => array(
        'singular' => '',           // List display name singular
        'plural' => '',             // List display name plural
        'post_status' => 'publish', // Post status on create the list (publish|private)
        'label' => '',              // Default value for button label
        'icon' => ''                // Default value for button icon (add|bookmark|gift|heart|star|none)
    )
);
``

== Screenshots ==

1. Screenshot from downloads settings screen

2. Screenshot from downloads settings screen (Settings for a button)

3. Screenshot from front end (Theme: vendd)

4. Screenshot from front end after add to list (Theme: vendd)

== Upgrade Notice ==

== Changelog ==

= 1.0 =
* Initial release