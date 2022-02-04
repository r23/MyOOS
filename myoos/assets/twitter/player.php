<!--
/* @license
 * Copyright 2021 Google Inc. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the 'License');
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an 'AS IS' BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
-->
<?php
define('OOS_VALID_MOD', true);


if (is_readable('../../includes/local/configure.php')) {
    require_once '../..//includes/local/configure.php';
} else {
    require_once '../..//includes/configure.php';
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><code>&lt;model-viewer&gt;</code> Player</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
  <meta name="color-scheme" content="dark light">

  <script type="module" src="<?php echo OOS_HTTPS_SERVER . OOS_SHOP . 'js/model-viewer/dist/model-viewer.min.js'; ?>"></script>

  <style>
    html {
      height: 100%;
    }

    body {
      height: 100%;
      margin: 0;
    }

    model-viewer {
      display: block;
      width: 100%;
      height: 100%;
      --poster-color: #fff0;
    }

    model-viewer::part(default-progress-mask) {
      display: none;
    }

    model-viewer::part(default-progress-bar) {
      background-color: rgba(127, 127, 127, 0.8);
    }

    /* This keeps child nodes hidden while the element loads */
    :not(:defined) {
      display: none;
    }
  </style>
</head>

<body>
  <model-viewer
    id="mv"
    seamless-poster
    environment-image="neutral"
    shadow-intensity="1"
    autoplay
    ar-modes="webxr scene-viewer quick-look"
    camera-controls
    auto-rotate
    interaction-prompt-threshold="1500"
  >

  <script type="module">
    const modelViewer = document.querySelector('#mv');
    const queryParams = window.location.search.substring(1).split('&');
    for (const param of queryParams) {
      const keyVal = param.split('=');
      const key = keyVal[0];
      const val = decodeURIComponent(keyVal[1].replace(/\+/g, ' '));
      if (key == 'style') {
        modelViewer.style.cssText = val;
      } else {
        const num = Number(val);
        modelViewer[key] = isFinite(num) ? num : val;
      }
    }
    // Work-around for a bug
    modelViewer.ar = true;
  </script>
</body>