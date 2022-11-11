=== Three Object Viewer ===
Requires at least: 5.7
Tested up to: 6.1
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author: antpb

A WordPress plugin for drag and drop 3D content creation compatible with most XR devices.

== Description ==
Welcome to the metaverse, WordPress!
The Three Object Viewer plugin is a drag and drop way to publish virtual worlds or 3D content using your WordPress site. The plugin supports AR VR and 2D in a browser window and runs React Three Fiber under the hood with support for the three-omi audio emitter extension.

## Drag and drop WebXR
The three object viewer plugin makes it possible to drag and drop 3D (glb) files into your WordPress block editor content. Wherever you can put a block, you can put a virtual world.
Your website can truly become a virtual world when your visitors click the “Enter in VR” button. When a user enters the scene, they will have the ability to teleport throughout the environment. More exciting VR features to come!

## Implements Open Metaverse Interoperability Extensions
This plugin supports the three-omi package built by contributors of the Open Metaverse Interoperability group. As new extensions are added, this plugin will be updated to support those features. Some potential components in the future include physics and collision events.

## Currently Supported Three OMI Extensions

Audio – Play both spatial and global audio in your scenes by creating a scene here in the build.xpportal.io Spoke editor. From there you can export a glb file that supports the Three OMI Audio Emitter Extension.

Colliders

Links

== Installation ==
This plugin can be installed directly from your WordPress site.

1. Log in to your WordPress site and navigate to **Plugins &rarr; Add New**.
2. Type "Three Object Viewer" into the Search box.
3. Locate the Three Object Viewer plugin in the list of search results and click **Install Now**.
4. Once installed, click the Activate button.

It can also be installed manually using a zip file.

1. Download the Three Object Viewer plugin from WordPress.org.
2. Log in to your WordPress site and navigate to **Plugins &rarr; Add New**.
3. Click the **Upload Plugin** button.
4. Click the **Choose File** button, select the zip file you downloaded in step 1, then click the **Install Now** button.
5. Click the **Activate Plugin** button.


== Changelog ==

= 1.0 =
New Environment Block for building feature rich experiences. This is the start to something big. 

3D Image Block- The Image block enables position and rotation settings for any image pulled from your media library. Support for transparency and a future update will include the ability to set external urls as the source.- 3D Video Block- Similar to image, the video block enables content creators to select video assets from the Media Library to attach to a plane in 3D space. I plan to add the ability to select external urls as well as custom mesh objects to render video on. The block can be paused and played by focusing your view in the direction of the video and clicking.

3D Model Block- The model block supports adding usdz, vrm, and glb files to your environment. This block has the ability to loop animations and be set to collidable so visitors can walk on the mesh surface.

Spawn Point Block- define where your visitors land when they enter your world.

Portal Block- Enable metaverse traversal or dive deeper into your site using urls and collidable objects to trigger traversal. There are settings to control the label and positioning of the label so your visitors know where they are going. This is going to be revamped after release to bring a prompt before traversing to ensure no unintended behavior.

Text Block- Use the text block to add text content inside of your worlds. You can define a color, position, and scale of the block of text. There will be more parameters in future updates.

3D Sky Block- Wrap your world in a 360 spherical panoramic to simulate skies

More to come! V1 will almost certainly be buggy, but please report any issues at github.com/antpb/three-object-viewer

= 0.6.3 =
*  Fix: Uploads were not merging allowed types. This update restores prior upload functionality with new usdz type.

= 0.6.2 =

*  Adds USDZ support to block (note, usdz files must not contain usdc files)
*  Updates Three.js to 144
*  Allows uploads of usdz files

= 0.6.1 =

*  Update rigibody types for vr objects. Sorry for the bouncy files! :)

= 0.6.0 =

*  Update react three fiber.
*  Update three omi to factor in autoplaying prop name change.
*  Fixes camera issues and sets better defaults. NOTE: default zoom has changed to 1. You will likely see visual regressions from this update if you have not changed the defaults. 
*  Fixes color pallete default value.
*  Support VRM Materials.
*  Multi Block Support. You can now have as many Three Object Blocks as you want per post and archive.

= 0.5.0 =

*  Fix animation build.

= 0.4.0 =

*  Fix animation front end render.

= 0.3.0 =

*  Adds animation support in the Three Object Block.

= 0.2.0 =

*  WordPress.org Release Version

= 0.1.0 =

*  Initial version.
