===Plugin Name===
Long Description
Contributors: mfields
Donate link: http://mfields.org/donate/
Tags: longdesc, long description, media, image, accessibility, WAI, a11y, screen reader
Requires at least: 3.1
Tested up to: 3.1
Stable tag: trunk

Automatically adds a longdesc attribute to your images when you insert them into the post body if a description has been written.

==Description==
Automatically adds a longdesc attribute to your images when you insert them into the post body if a description has been written. A page will be created that shows the information that you typed into the description field of your image as well as a link back to the place in the document where the reader left off.

You can create a custom template in your theme's folder to further style the Long Description page. I would suggest creating a copy of the longdesc-template.php file from this plugin in your active theme (or child theme) and reworking it to fit your needs.

==Changelog==

= 1.2 =
* Refactor most of the code.
* Escape all output.

= 1.1 =
* load_plugin_textdomain() added.
* longdesc_return_anchor() added.
* longdesc-template.php added.
* Support for theme files added.
* Return to Article functionality added.
* No loner using wp-admin/admin-ajax.php as delivery method.

= 1.0.2 =
Now using both wp_ajax_longdesc and wp_ajax_nopriv_longdesc.

= 1.0.1 =
* Changed action from wp_ajax_longdesc to wp_ajax_nopriv_longdesc.

= 1.0 =
* Original Release - Works with WordPress 3.0.1.






==Installation==
1. [Download](http://wordpress.org/extend/plugins/taxonomy-list-shortcode/)
1. Unzip the package and upload to your /wp-content/plugins/ directory.
1. Log into WordPress and navigate to the "Plugins" panel.
1. Activate the plugin.