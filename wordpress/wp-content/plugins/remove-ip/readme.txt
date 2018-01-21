=== Remove IP ===
Contributors: gui.do
Tags: comments, IP
Requires at least: 2.8
Tested up to: 4.4
Stable tag: trunk

A simple plugin to not log IPs from comments.

== Description ==

Remove IP it's a really-really-simple plugin to not log the IP address from the people that comment on your wordpress 
installation. 

This plugin will be useful to people that cannot/or don't want to use [libapache2-mod-removeip](http://riseuplabs.org/privacy/apache/ "libapache2-mod-removeip") (because you don't use 
apache or don't want to wipe out the IP logging on all vhosts).

== Installation ==

1. Upload the removeip folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. voila

== Frequently Asked Questions ==

= Do I need this? =

If you are reading this it's probably because you are interested on your commenters anonymity. If you don't, you don't need this plugin. 

= Is this going to annonymize the logs of my webserver? =

No, sorry. Try libapache2-mod-removeip if you use apache2.

= I installed libapache2-mod-removeip, do I need this plugin too? =

No, you don't. The libapache-mod-removeip will hide the IP address from the commenters to wordpress, the same as this plugin does.

= Can I use it with wordpress.com stats/google analytics? =

Sure, but I'm not sure that you really want to let google or wordpress.com register the IP address from your commenters.

= Your plugin is lame! =

Yep, I know. I had to make it work for a project that uses nginx as webserver and cannot switch to apache and we got tired 
of editing wordpress core files... so I needed it. And it's always nice to share :)

== Changelog ==

= 0.1 =

* First version.

