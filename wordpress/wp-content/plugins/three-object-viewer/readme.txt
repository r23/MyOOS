=== Three Object Viewer ===
Requires at least: 5.7
Tested up to: 6.2
Requires PHP: 7.2
Stable tag: 1.3.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author: antpb

A WordPress plugin for drag and drop 3D content creation compatible with most XR devices.

== Description ==
## Welcome to the metaverse, WordPress!
Elevate your WordPress site to new dimensions with our 3OV plugin, an intuitive drag-and-drop tool for effortlessly integrating virtual worlds, 3D content, AR, and VR experiences. Built on React Three Fiber, our plugin supports a wide range of interoperable extensions and comes equipped with cutting-edge AI features implemented in a NPC block. Get ready to transform your website into an immersive metaverse destination!

## Seamless WebXR Integration with Drag and Drop Functionality
With the 3OV plugin, embedding 3D (glb) files into your WordPress block editor content has never been easier. Simply drag and drop files to create virtual worlds anywhere you can place a block. Engage your visitors with a click of the "Enter in VR" button, allowing them to teleport throughout your virtual environment. Stay tuned for even more exhilarating VR features!

## Embrace the Possibilities of Interoperability with Supported OMI, KHR, and Other Extensions
KHR_audio: Immerse your audience in both spatial and global audio by designing scenes with the build.xpportal.io Spoke editor. Export glb files compatible with the Three OMI Audio Emitter Extension.
OMI_collider: Enable walking on surfaces for a more interactive experience.
OMI_link: Metaverse traversal at its finest! Walk into objects with the omi_link extension to simulate hyperlink actions.
OMI_spawn_point: Customize your player's starting point in the virtual world.
SXP_personality: Bring your NPCs to life with Magick ML or our Alchemy Worker through the innovative 3OV NPC Block.
Elevate your WordPress site into a truly immersive virtual experience with the 3OV plugin. Try it now and unlock a whole new world of possibilities!

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
= 1.3.7 =
Fixed: NPC text was falling into the ground. Moved up closer to NPC head position.

= 1.3.6 =
Added: Compatibility with gltf files using Draco compression
Added: Custom geometry options for 3D Video Block. When selecting a glb to use as your video surface, ensure that you name the object in your glb "screen". More info in this video: https://twitter.com/threeov/status/1643098207361138691?s=20

= 1.3.5 =
Added: GPT-4 compatibility for NPCs. You can use https://alchemy-gpt-4.sxp.digital as your ai url to use GPT-4 or host your own using the very same worker: https://github.com/xpportal/alchemy-worker-gpt4. (Note: you'll need access to gpt-4 for this to work. You are not able to use our keys.)

= 1.3.4 =
Fixed: Incompatibility between Three.js and three-vrm was causing vrm files to fail to render. This should be in working order again.

= 1.3.3 =
Fixes: 3OV is now fully compatible with the iframed/site editor. Yay! This was a scary bug that I didnt know how we'd solve. We we're able to contribute a fix upstream in Three.js! Please do report any problems.

= 1.3.2 =
Fixes: Media Library logic in the settings page was using the incorrect action. h/t emotionull, thanks for the report!

= 1.3.1 =
Fixes: downgrade r3f to compatible version

= 1.3.0 =
Added: New Sky Block controls to allow skies without images.
Fixes: Environment block was previously not allowing to change the environment.
Fixes: Allows for glb files to be set as default animation in the settings page.

= 1.2.2 =
Added: New transform control buttons in the editor to toggle between scale, rotation, translation.
Added: Now Environment Block initializes with a starter grid object and a spawn point to get you started quicker.
Fixes: Errors in WordPress messages from NPC responses were not scoped to root Namespace

= 1.2.1 =
Fixes: Clears input after sending a message to a NPC
Removes cruft that made it into the release. Pardon my logs. :)

= 1.2.0 =
Added: NPC Block for AI assistants in your 3OV worlds - you can use our endpoint up to 15 free requests before you'll need to use your own openai api key. We'll be publishing a post soon on how to configure your own logic in MagickML or your own worker.
Added: 3OV Settings panel to control global 3OV settings.
Added: Setting for default VRM animation. You can use any mixamo and similar FBX file as the source for your NPC and VRM file's default animations.
Added: Three Object Block now uses default idle animation for fbx files. Try it in AR!
Added: Settings for AI settings. You can configure your endpoint for your ai system. If you are using MagickML, you will not need to define an OpenAI api key. Just point it to your Magick instance. If you would like to use our worker, define your openai key there and everything should work as expected.
Fixed: Namespace collisions.
Fixed: Adjusted lighting to be slightly darker. Things were getting washed out.
Fixed: adjusted the character controller height to 0.33. Things were feeling low in all worlds so this could revert if folks arent happy. (Will be configurable in future release)


= 1.1.0 =
Fixed: Controller was loading characters too high. Scale has changed so scenes may need to be verified to be as previously saved.
Fixed: VRM objects will load with an idle animation.
Added: New editor sidebar for selecting objects in the editor.
Added: Press F now when in the editor
Added: Hold shift while moving to run.

= 1.0.9 =
Fixed: Spawn - Spawn block now properly loads a player in the spawn location.
Added: Initial support for OMI_spawn_point.

= 1.0.8 =
Added: Click event for audio objects that contain KHR_audio. Improvements to come. To set a KHR_audio object to be interactable simply make the object collibable.
Added: Video - Interactions to play/pause a video. A paused video will now show a play icon to resume the video source. Video audio to come soon!
Added: Portal - VR interactions for Portal block. Now clicking on the portal block will take you to the destination.
Fixed: Video - autoPlay property was not being properly respected.
Fixed: Model and Environment Block - Animations were previously broken since the launch of the Environment Block. All should be good now with the caviat that you cannot use the same object twice in a scene. An update will fix this very soon.
Fixed: Sky Block - fixed issue where teleportation to the sky was possible. Stay on the ground!

= 1.0.7 =
Fix: removes wp_texturize that was breaking url strings on the front end.

= 1.0.6 =
Fix: Sky blocks were causing incredibly poor performance in VR. This is fixed by using a more optimized primitive for the skybox.

= 1.0.5 =
Added: Openbrush support in the Model Block and Three Object Block. To use, add a Model Block to a scene and select a Tilt Brush glb file. Plugin can be downloaded at: https://github.com/xpportal/three-object-viewer-three-icosa/releases/tag/0.1.1
Fix: Initialize text at a better size when adding a Text Block

= 1.0.4 =
Fix: Scaling and alignment of Portal text was not true on the front end to what was saved in the editor. This update fixes the text positining and centers it to the object's 0,0,0 position.

= 1.0.3 =
Fix: Removes collider debug.

= 1.0.2 =
Fix: Environment block settings were not respected on the front end. Should be good now!
Fix: range controls in the Environment block were in a broken flex.

= 1.0.1 =
Environment blocks without a OMI collider will be treated as a trimesh collider. This means that every item used in the Environment block will be collidable/walkable.

Image Block Edit Controls now update correctly. Fixed a bug where an image would return to its previous location after chaging position.


= 1.0.0 =
New Environment Block for building feature rich experiences. This is the start to something big. 

3D Image Block- The Image block enables position and rotation settings for any image pulled from your media library. Support for transparency and a future update will include the ability to set external urls as the source.- 3D Video Block- Similar to image, the video block enables content creators to select video assets from the Media Library to attach to a plane in 3D space. I plan to add the ability to select external urls as well as custom mesh objects to render video on. The block can be paused and played by focusing your view in the direction of the video and clicking.

3D Model Block- The model block supports adding usdz, vrm, and glb files to your environment. This block has the ability to loop animations and be set to collidable so visitors can walk on the mesh surface.

Spawn Point Block- define where your visitors land when they enter your world.

Portal Block- Enable metaverse traversal or dive deeper into your site using urls and collidable objects to trigger traversal. There are settings to control the label and positioning of the label so your visitors know where they are going. This is going to be revamped after release to bring a prompt before traversing to ensure no unintended behavior.

Text Block- Use the text block to add text content inside of your worlds. You can define a color, position, and scale of the block of text. There will be more parameters in future updates.

3D Sky Block- Wrap your world in a 360 spherical panoramic to simulate skies

More to come! V1 will almost certainly be buggy, but please report any issues at github.com/antpb/three-object-viewer

= 1.0.1 =
Environment blocks without a OMI collider will be treated as a trimesh collider. This means that every item used in the Environment block will be collidable/walkable.

Image Block Edit Controls now update correctly. Fixed a bug where an image would return to its previous location after chaging position.


= 1.0.0 =
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
