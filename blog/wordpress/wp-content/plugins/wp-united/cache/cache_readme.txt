WP-UNITED CACHE
---------------

What is in this folder?

In a bid to speed up reverse integrations, and to reduce memory load on your server, WP-United has introduced two basic caching methods.

This folder holds the cache for:
(a) the WordPress core execution cache; 
(b) the WordPress header and footer cache for when phpBB is inside WordPress (in 'simple' mode).
(c) Cached CSS for when CSS Magic is enabled
(d) Cached template Voodoo instructions
(e) Cached plugin modifications

More information about these options, and the switches to turn them on or off can be found in your wp-united/options.php file.

IMPORTANT NOTES:

1. Whenever you upgrade WP-United or WordPress, you MUST delete any files that have appeared in this folder. (Don't worry, they will be automatically recreated)
2. Your web server MUST be able to write to this cache folder for the caching options to work!
3. If you turn on the header/footer cache for WordPress, dynamic elements in your WordPress header or footer (with the exception of the title), will NOT be dynamic on a phpBB-in-WordPress (simple) page.

You may delete this file -- it is simply a placeholder so that this cache folder gets created by AutoMod.

John Wells
04 June 2007
Last Modified 18 May 2009
