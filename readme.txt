=== Custom Simple Rss ===
Contributors: danikoo, videohead
Donate link: 
Tags: rss, custom rss, feed ,custom feed
Requires at least: 4.0.1
Tested up to: 5.5
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin to create your own Custom Simple RSS Feed according to parameters you choose

== Description ==

A plugin to create a your own Custom Simple RSS Feed 
according to parameters you choose!

**in simple words:** 
Ever wanted an rss feed for just a specific Author and specific Category?

Or Even an rss feed for a specific Custom Field ???

Well... now you got it !

The plugin does not alter your default wordpress feeds - it enables you to display feeds **on the fly** via specific url with pre defined url query parameters.

**for example:**

display only 5 items from specific category order by name descending:

www.yordomain.com/?call_custom_simple_rss=1&csrp_posts_per_page=5&csrp_orderby=name&csrp_order=DESC&csrp_cat=4


**Filter items by:**

* category id
* post type
* post status
* tag
* and even meta keys and values!

**Order by:**

* name
* date
* author
* ID
* etc

**More Features:**

* number of items to return
* show post thumbnail or not?
* show post custom fields (espically usefull if your using your rss as an affliate feed)
* ability to add cloud hosted media for improved video performance


== Installation ==

1. Upload the "Custom Simple Rss" plugin to your website 
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go To "settings" and there you will find all you need under "Custom Simple Rss Plugin Options"
4. Good Luck

== Frequently Asked Questions ==
No Questions

== Screenshots ==

1. set defaults screen

== Changelog ==

**1.5**

2015-09-11 06:33 version 1.5 - new!! added feature to hide content from feed or show 2 types of content(html,full)

**1.4**

2015-07-08 12:45 first launch

2015-07-16 09:45 adding author support

2015-08-14 16:23 minor bug fix - set defaults on first activate

2015-09-10 11:23 apllied the 'the_content' filter on content encoded to extract shortcodes

2015-09-10 18:33 removing trash from content encoded and returning clean html
