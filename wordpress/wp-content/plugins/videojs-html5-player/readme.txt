=== Videojs HTML5 Player ===
Contributors: naa986
Donate link: https://wphowto.net/
Tags: videojs, video, player, embed, html5
Requires at least: 4.2
Tested up to: 6.0
Stable tag: 1.1.7
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Embed video file beautifully in WordPress using Video.js HTML5 Player. Embed HTML5 compatible responsive video in your post/page with Video.js.

== Description ==

[Video.js HTML5 Player](https://wphowto.net/videojs-html5-player-for-wordpress-757) is a user-friendly plugin that supports video playback on desktop and mobile devices. It makes super easy for you to embed both self-hosted video files or video files that are externally hosted using Video.js library.

https://www.youtube.com/watch?v=uF-V6qGvcu8&rel=0

=== Video.js HTML5 Player Features ===

* Embed MP4 video files into a post/page or anywhere on your WordPress site
* Embed responsive videos for a better user experience while viewing from a mobile device
* Embed HTML5 videos which are compatible with all major browsers
* Embed videos with poster images
* Embed videos using videojs player
* Automatically play a video when the page is rendered
* Embed videos uploaded to your WordPress media library using direct links in the shortcode
* No setup required, simply install and start embedding videos
* Lightweight and compatible with the latest version of WordPress
* Clean and sleek player with no watermark
* fallbacks for other HTML5-supported filetypes (WebM, Ogv)
* HTTP streaming

=== How to Use Video.js HTML5 Player ===

In order to embed a video create a new post/page and use the following shortcode:

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4"]`

Here, "url" is the location of the MP4 video source file (H.264 encoded). You need to replace the sample URL with the actual URL of the video file.

= Video Shortcode Options =

The following options are supported in the shortcode.

**WebM**

You can specify a WebM video file in addition to the source MP4 video file. This parameter is optional.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" webm="https://example.com/wp-content/uploads/videos/myvid.webm"]`

**Ogv**

You can specify a Ogv video file in addition to the source MP4 & WebM video files. This parameter is optional.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" webm="https://example.com/wp-content/uploads/videos/myvid.webm" ogv="https://example.com/wp-content/uploads/videos/myvid.ogv"]`

**Width**

Defines the width of the video file (Height is automatically calculated). This option is not required unless you want to limit the maximum width of the video.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" width="480"]`

**Preload**

Specifies if and how the video should be loaded when the page loads. Defaults to "auto" (the video should be loaded entirely when the page loads). Other options:

* "metadata" - only metadata should be loaded when the page loads
* "none" - the video should not be loaded when the page loads

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" preload="metadata"]`

**Controls**

Specifies that video controls should be displayed. Defaults to "true". In order to hide controls set this parameter to "false".

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" controls="false"]`

When you disable controls users will not be able to interact with your videos. So It is recommended that you enable autoplay for a video with no controls.

**Autoplay**

Causes the video file to automatically play when the page loads.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" autoplay="true"]`

**Poster**

Defines image to show as placeholder before the video plays.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" poster="https://example.com/wp-content/uploads/poster.jpg"]`

**Loop**

Causes the video file to loop to beginning when finished and automatically continue playing.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" loop="true"]`

**Muted**

Specifies that the audio output of the video should be muted.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.mp4" muted="true"]`
<br>
=== Video.js HTTP Streaming ===

The plugin supports the m3u8 file format that can be used for Video.js HTTP Streaming. It allows you to play HLS, DASH, and other HTTP streaming protocols with Video.js, even where they are not natively supported.

`[videojs_video url="https://example.com/wp-content/uploads/videos/myvid.m3u8"]`


For detailed documentation please visit the [Videojs HTML5 Player](https://wphowto.net/videojs-html5-player-for-wordpress-757) plugin page

== Installation ==

1. Go to the Add New plugins screen in your WordPress Dashboard
1. Click the upload tab
1. Browse for the plugin file (videojs-html5-player.zip) on your computer
1. Click "Install Now" and then hit the activate button

== Frequently Asked Questions ==

= What is Video.js? =

Video.js is a web video player built from the ground up for an HTML5 world. It supports HTML5 video playback on desktop and mobile devices.

= How do I run Video.js? =

Install the Video.js plugin and add a shortcode to your WordPress post/page.

= Is Video.js open source? =

Yes.

= Does Video.js plugin support HLS? =

No.

= What media formats does Video.js plugin support? =

MP4, WebM and Ogv.

== Screenshots ==

1. Video.js Player Demo

== Upgrade Notice ==
none

== Changelog ==

= 1.1.7 =
* Added support for Video.js HTTP Streaming.

= 1.1.6 =
* Updated Video.js to 7.14.3.

= 1.1.5 =
* Updated Videojs to 7.10.1.

= 1.1.4 =
* Made some security related improvements in the plugin

= 1.1.3 =

* Videojs play button is now centered by default.

= 1.1.2 =

* Videojs HTML5 Player is now compatible with WordPress 4.9.

= 1.1.1 =

* Added support for playsinline attribute which allows a video to play inline on iOS (the video will not automatically enter fullscreen mode when playback begins).

= 1.1.0 =

* Videojs script is now enqueued in the footer to avoid a JavaScript setup error.

= 1.0.9 =

* Made jQuery a dependency for the videojs script.

= 1.0.8 =

* Updated the translation files so the plugin can take advantage of language packs.
* Videojs HTML5 Player is now compatible with WordPress 4.4.

= 1.0.7 =

* Added a new shortcode parameter to accept Ogv as a video source format.

= 1.0.6 =

* Added a new shortcode parameter to accept WebM as a video source format.

= 1.0.5 =

* Updated the Videojs library to 5.0.0

= 1.0.4 =

* Videojs HTML5 Player is now compatible with WordPress 4.3

= 1.0.3 =

* Added an option to mute the audio output of a video
* Added an option to loop a video

= 1.0.2 =

* Added an option to show/hide controls
* Added an option to set preload attribute

= 1.0.1 =

* First commit
