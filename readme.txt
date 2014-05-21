=== Plugin Name ===
Contributors: arsdehnel
Donate link: http://arsdehnel.net/plugin/post-access-controller/
Tags: security, visibility, post access, access control
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: "trunk"
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to control who has access to a particular post by indicating users individually or by creating access control groups that can then be assigned to posts for repeated groupings of users.  This takes the place of and enhances the "visibility" option that is part of the WP Core post maintenance screen.

== Description ==

Initially this was developed for use on my own site to allow proposals I created for clients to live within my WordPress site as a custom post type but one that (even if someone stumbled across the URL) they wouldn't be allowed to see.  But the uses are already going beyond that for me and I'm sure there are many implementations that I haven't even thought of.

### Post Access

Within the *edit* form for any of your post types (see below for configuration options that will determine which types of posts get this option) there is a new meta box that allows you to indicate what type of access controls you would like to enforce on that post.  If you choose "By Individual" then you are given a list of all the current users for that WordPress installation.  You can check as many of these checkboxes as you'd like to individually give those users access to that particular post.  Similarly you can choose "By Group" from that same drop down and you will be presented with a listing of groups that users can be assigned to for more reusable combinations of users.

### User Groups

As mentioned above, each post can have one or many user groups that are given access to a particular post.  These groups are maintained through the "User Groups" forms that live within the "Users" menu in the WordPress admin.  You can create as many user groups as you'd like and assign users to whichever groups make sense.  There is no limit to the number of users within a group and there is no limit to the number of groups a given user can be in.  These groups can make this a great tool for websites for organizations that have committees or teams that need access to some information but that information is not public.  A user group can be setup and re-used over and over.  And then if a member leaves (or is added) it only needs to be maintained in that one group definition rather than adjusting all of your posts.

These admin forms have been built with all the WordPress admin tools and structures so they feel like they are part of WP Core.  Easy to browse through, filter results and maintain all of your groups and their members.

### Configuration Options

There are just a few configuration options for this plugin available through the Settings menu:

1. You can specify which post types should even have this option on the edit form.  This can be nice when there are multiple administrators and authors and only some post types should have this sort of option.
2. There is a place to enter the default access denied message that a visitor would see if they tried to access a post that they were not allowed to see based on the controls setup.  This can be overridden within each post but it's nice to have a default if it's always going to be the same message you want to tell visitors.
3. To make this fit with your preference on how the edit post form feels you can adjust the location that this new meta box should go to.  This is a little generic because of how many options there are for WordPress to layout their admin but you get all the options that WordPress provides for locations on that form.
4. Lastly you can control the "priority" of where that meta box falls in the location you specified.  For instance if you put a high priority on something and put it in the "Along Right Side" location then it will be at the top of the right side above the publish box.  Any other priority setting for the right side location setting will result in it being below the publish box.

== Installation ==

Installation of the Post Access Controller is pretty standard.

1. Download the zip archive of the plugin
2. Extract the archive
3. Upload that archive to the /wp-content/plugins/post-access-controller/ folder
4. Go to the Plugins menu in the admin of your WP site
5. Click Activate in the Post Access Controller line
6. Go to the Settings -> Post Access Controller page to get a few things setup and configured.
7. Done! You're ready to start editing or creating posts with all the access control you need.

== Frequently Asked Questions ==

= How secure is Post Access Controller? =

The short answer: pretty darn secure but don't put your bank information out there.  For 98% of users they would have no way of getting around the checks and controls that this puts into place.  It will prevent the stumbling across a link that isn't for them (such as one forwarded in emails or that they maliciously got from someone else) and it will prevent the page from coming up in any search results. Without being a hacker and knowing the approaches that are taken to compromise a WordPress site it would be impossible to say that this covers every possible attack situation.  However, it uses the built-in WP Core actions and variables for the access checking so it is as secure as WP itself.

== Screenshots ==

1. First thing to do is to get to the Settings -> Post Access Controller page and make sure the configuration options are all what you would like.
2. This is the listing of all the access control groups that have been setup and their maintenance options ("edit" or "archive")
3. Clicking edit on the listing page will bring up the group maintenance form where you can specify who is in that group.
4. Once there are groups setup then the user maintenance page also allows for maintenance of the groups that the particular user is included in.=
5. On the post maintenance page with the settings making the post access control box on the right side and high priority.

== Changelog ==

= 0.9.2 =
* Adjusted what is displayed to users that don't have access to a page that get to the URL representing that page.  It was showing a 404 because WordPress said there was nothing on that page for them but now it will show the Access Denied header and the indicated No Access message.

= 0.9.1 =
* Added interim option to allow the WP Core post visibility option if enabled in the Post Access Controller settings page

= 0.9.0 =
* Major change in how data is stored, WILL BREAK existing implementations and require either re-entry of access control groups and access control user selections or a custom conversion script.  Please make support request if you would like a conversion script.
* Bug fix to remove posts from the listing that user does not have access to
* Created custom post type and converted from custom database tables to use the core wp_posts and related meta data
* Cleaned up admin pages again to really feel like it operates like WP Core
* Adjusted necessary pieces to make it work within the WP Plugin Library

= 0.5.0 =
* Rewrote admin pages to use WP classes so the feel of the pages matched the rest of core

= 0.1.0 =
* Initial version with custom database tables and non-WP looking admin pages

== Upgrade Notice ==

= 0.9 =
See changelog, there are some major backend changes with this version that could cause problems with existing installations of this plugin.

= 0.8 =
First version publicly accessible via WordPress.org

== Future Enhancements ==

Any good plugin will almost always have things that it could do that it doesn't right now.  Rather than keeping those internal it seems like a good idea to put them out there for discussion or at least so you know what might be coming.  That way if this plugin looks like it does almost what you're doing but you know it's going to do all of what you need in the future you can get on board now.  **Also please feel free to request features!**

* Create a settings page option to limit which users can maintain their own groups.  Sometimes users might be subscribers and shouldn't be able to add themselves to an admin-like group (for instance).
* Add filter/sorting options to the listing of users on the group maintenance page for sites where there are hundreds or thousands of users the current inputs would be difficult and frustrating.
* Add filter/sorting options to the listing of users or groups on the post maintenance page.  Even once groups are setup maybe there are lots of groups or some posts just require the setting of users individually.  The current method of sorting just by name might get majorly time-consuming on sites that have lots of users.
* Add logic to the post maintenance page to make sure that at least one user or group has access to the post

**Have an idea? Submit it using the Support menu option and I'll let you know about getting it added!**