<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

// Create a nonce RANDOM_VALUE
$nonce = bin2hex(random_bytes(16));
// Store the nonce RANDOM_VALUE in the session
// $_SESSION['nonce'] = $nonce;
// Send the CSP header with the nonce RANDOM_VALUE

// header("Content-Security-Policy: script-src 'nonce-$nonce' 'unsafe-eval'");
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
    <title>Webbasierte virtuelle Erlebnisse im Metaversum</title>
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="themes/metaverse/css/vr-commerce.min.css">

	<!-- Bootstrap JS Dependencies -->
	<script nonce="<?php echo $nonce; ?>" src="themes/metaverse/js/vendor.min.js"></script>

	<!-- Virtual Experiences JS Dependencies -->
   	<script nonce="<?php echo $nonce; ?>" src="static/js/runtime-main.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/572.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/995.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/203.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/434.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/454.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/861.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/58.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/710.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/223.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/850.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/848.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/503.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/569.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/687.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/448.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/109.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/339.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/406.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/477.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/main.js"></script>
	
<style>
body, html {
  width: 100%;
  height: 100%;
}

body {
  background-color: #232323;
}

body.AR-container {
  margin : 0px; 
  overflow: hidden;
  background-color: transparent;
}

#splash {
  position: fixed;
  z-index: 99999;

  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  
  margin: auto;

  font-size: 14px;
  font-family: sans-serif;
  color: #fff;

  background-color: rgba(0, 0, 0, .99);
  transition: all 1s ease-out;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

#splash .loading {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  border: 0.25rem solid rgba(255, 255, 255, 0.2);
  border-top-color: #fff;
  animation: spin 1s infinite linear;
}

.controlKeys {
  position: absolute;
  width: 7rem;
  left: 5%;
  bottom: 13%;
}
</style>

<script nonce="<?php echo $nonce; ?>">
window.addEventListener('enter-vr', e => {
  if (AFRAME.utils.device.checkHeadsetConnected()) {
    if (document.getElementById('cursor')) {
      document.getElementById('cursor').remove()
    }
  }
});

</script>
  </head>
<body>

	<div id="splash">
		<div class="loading"></div>
			<div class="container-fluid ">
				<div class="row">
					<div class="col px-5" style="position: relative; margin-top: 400px;">
						<div style="position: absolute; bottom: 0;">
							<h1>MyOOS Immersive-Showroom</h1>
							<p>Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die 
							Blindtexte. Abgeschieden wohnen sie in Buchstabhausen an der Küste des Semantik, eines 
							großen Sprachozeans. Ein kleines Bächlein namens Duden fließt durch ihren Ort und 
							versorgt sie mit den nötigen Regelialien. Es ist ein paradiesmatisches Land, in dem einem 
							gebratene Satzteile in den Mund fliegen. Nicht einmal von der allmächtigen Interpunktion 
							werden die Blindtexte beherrscht – ein geradezu unorthographisches Leben.</p>
						</div>
					</div>
					<div class="col-4">
					</div>
					<div class="col px-5" style="position: relative; margin-top: 400px;">
						<div style="position: absolute; bottom: 0;">
							<img class="controlKeys" src="image/key_controls.png" alt="Steuerungstasten">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<a-scene nonce="<?php echo $nonce; ?>" embedded="false" vr-mode-ui="enabled: true"><!-- creates a UI element for the VR mode -->
		<a-assets>
			<img id="skyTexture" src="texture/kloofendal_43d_clear_puresky.jpg" preload="auto">

			<a-asset-item id="navmesh" src="model/hall-navmesh.glb" preload="auto"></a-asset-item>
			<a-asset-item id="hall" src="model/hall.glb" preload="auto"></a-asset-item>

			<!-- NOTE: Playing sound on iOS — in any browser — requires a physical user interaction. -->
			<!-- More info here: https://aframe.io/docs/1.0.0/components/sound.html -->
			<!-- Also, on Desktop devices, autoplay works in Firefox and doesn't work in Chrome  -->
		    <audio id="river" preload="auto" src="sound/birds-singing-calm-river-nature-ambient-sound-127411.mp3"></audio>

			<a-asset-item id="ocean" src="model/ocean.glb"></a-asset-item>

        	<img id="play" src="image/play.png">
        	<img id="pause" src="image/pause.png">
			<video id="my-video" autoplay loop="true" src="video/produktanimation.mp4"></video>
		</a-assets>

		<!-- Create an ambient light with a light grey colour -->
		<a-entity light="type: ambient; color: #CCC"></a-entity>  
		<!-- Create a point light with a white colour at the position -5 10 0 -->
		<a-entity light="type: point; color: #FFF; intensity: 0.8; distance: 20; decay: 2" position="-5 10 0"></a-entity> 
		<!-- Add your other entities -->

		<!-- Nav mesh. -->
		<a-entity id="ground" nav-mesh
                visible="false"
                position="0 0.001 20"
                gltf-model="#navmesh">
		</a-entity>

		<!-- Scene. -->
		<a-entity position="0 0 20"
                scale="1 1 1"
                gltf-model="#hall">
		</a-entity>
	
		<a-entity gltf-model="#ocean" position="0 10 -10" animation-mixer="clip: *; loop: true; timeScale: 0.25">
		</a-entity> 

        <!-- 360° Panorama -->
        <a-sky src="#skyTexture"
        	   sound="src: #river; autoplay: true; loop: true; positional: false; volume: 0.5">
        </a-sky>

		<!-- Video 16:9 -->
		<a-video src="#my-video" width="16" height="9" position="0.793  16.34 -47.75">
			<!-- Play/Pause -->
			<a-image id="videoControls" src="#play" position="0 -5 0" scale="0.5 0.5 1"
					 play-pause class="clickable">
			</a-image>
		</a-video>


		<!-- CAMERA RIG -->
		<a-entity id="rig"
                movement-controls="constrainToNavMesh: true;
                                   controls: checkpoint, gamepad, trackpad, keyboard, touch;"
                position="-7 0 21">
			<a-entity camera
                  position="0 1.6 0"
                  look-controls="pointerLockEnabled: true"
				  rotation="0 -90 0">
                        <a-entity id="cursor" cursor="fuse: false;" animation__click="property: scale; startEvents: click; easing: easeInCubic; dur: 50; from: 	0.1 0.1 0.1; to: 1 1 1"
                            position="0 0 -0.1"
                            geometry="primitive: circle; radius: 0.001;"
                            material="color: #CCC; shader: flat;"
                            >
                        </a-entity>
			</a-entity>

			<!-- Right Controller  -->
			<a-entity id="right-hand" laser-controls="hand: right" 
				oculus-touch-controls="hand: right" 
				vive-controls="hand: right" 
				raycaster="objects: .clickable; lineColor: #FF0000" 
				super-hands="colliderEvent: raycaster-intersection; colliderEventProperty: els; colliderEndEvent: raycaster-intersection-cleared; colliderEndEventProperty: clearedEls"> 
			</a-entity>


			<!-- Left Controller  -->		
			<a-entity id="left-hand" hand-controls="hand: left"
				super-hands="colliderEvent: hitstart; colliderEventProperty: els; colliderEndEvent: hitend; colliderEndEventProperty: clearedEls" 
				blink-controls="button: trigger; startEvents: triggerdown; endEvents: triggerup; cancelEvents: gripdown; collisionEntities: #ground">
			</a-entity>
			
		</a-entity>
	</a-scene>


<script nonce="<?php echo $nonce; ?>">
document.addEventListener('DOMContentLoaded', function() {
    const scene = document.querySelector('a-scene');
    const splash = document.querySelector('#splash');
    const loading = document.querySelector('#splash .loading');

    const emitEvent = (eventName, listeners) => {
        listeners.forEach((listener) => {
            const el = document.querySelector(listener);
            el.emit(eventName);
        })
    };

    const emitMediaEvent = (eventType, listeners) => {
        listeners.forEach((listener) => {
            const el = document.querySelector(listener);
            el.components.sound[eventType]();
        })
    };

    scene.addEventListener('loaded', function (e) {
        setTimeout(() => {
            loading.style.display = 'none';
            splash.style.backgroundColor = 'rgba(0, 0, 0, 0.85)';
			splash.style.display = 'none'; 
        }, 50);
    });
});


</script>

</body>
</html>