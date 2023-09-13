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
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,user-scalable=no,maximum-scale=1">
    <title>Web-Based Virtual Experiences in the Metaverse</title>
	
	<!-- Bootstrap Bootswatch theme CSS - other themes available here: https://bootswatch.com -->
	<link rel="stylesheet" href="themes/metaverse/css/vr-commerce.min.css" />

  <!-- Font Awesome -->
  <?php
  //   <link rel="stylesheet" href="assets/css/font-awesome-4.7.0/css/font-awesome.min.css" />
  ?>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="themes/metaverse/css/style.css" />

<link rel="stylesheet" href="themes/metaverse/css/preloader.css" />
	<!-- Bootstrap JS Dependencies -->
	<script nonce="<?php echo $nonce; ?>" src="themes/metaverse/js/vendor.min.js"></script>

	<!-- Virtual Experiences JS Dependencies -->
   	<script nonce="<?php echo $nonce; ?>" src="static/js/runtime-main.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/572.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/734.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/203.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/434.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/454.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/986.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/58.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/710.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/223.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/850.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/848.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/227.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/569.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/687.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/406.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/477.js"></script>
   	<script nonce="<?php echo $nonce; ?>" src="static/js/main.js"></script>

  </head>
<body>

<div id="preloader-modal" class="modal instructions-modal" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog modal-dialog__full" role="document">
    <div class="modal-content vertical-align text-center">
      <div class="col-md-6 col-md-offset-3">
        <div class="row">
          <div class="col-xs-12">
            <i class="preloader-modal__ok fa fa-check fa-5x text-success" aria-hidden="true"></i>
            <i class="preloader-modal__spinner fa fa-cog fa-spin fa-5x fa-fw text-muted" aria-hidden="true"></i>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <span class="progress-label">Loading 0%</span>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <button type="button" class="btn btn-default clearfix" data-dismiss="modal">Start</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

	<!-- slowLoad: true is only for demo purposes -->
	<a-scene nonce="<?php echo $nonce; ?>" embedded="false" vr-mode-ui="enabled: true" preloader="autoInject: false; clickToClose: true; autoClose: true; target: #preloader-modal; bar: #preloader-modal .progress-bar; label: #preloader-modal .progress-label; labelText: Loading {0}%; slowLoad: true; doneLabelText: Press Start To Play!;">

		<!-- creates a UI element for the VR mode -->
		<a-assets>
			<img id="skyTexture" src="texture/kloofendal_43d_clear_puresky.jpg" preload="auto">
			<a-asset-item id="navmesh" src="model/hall-navmesh.glb" preload="auto"></a-asset-item>
			<a-asset-item id="hall" src="model/hall.glb" preload="auto"></a-asset-item>
		<?php
		/*
			<a-asset-item id="gem" src="model/rupee.glb"></a-asset-item>
		*/
		?>
		</a-assets>
	<a-sky src="#skyTexture"></a-sky>
	
	 <!-- Erstellen Sie ein ambient light mit einer hellgrauen Farbe -->
	<a-entity light="type: ambient; color: #CCC"></a-entity>  
	<!-- Erstellen Sie ein point light mit einer weißen Farbe an der Position -5 10 0 -->
    <a-entity light="type: point; color: #FFF; intensity: 0.8; distance: 20; decay: 2" position="-5 10 0"></a-entity> 
	<!-- Fügen Sie Ihre anderen Entitäten hinzu -->

      <!-- Player. -->
      <a-entity id="rig"
                movement-controls="constrainToNavMesh: true;
                                   controls: checkpoint, gamepad, trackpad, keyboard, touch;"
                position="-7 0 21">
        <a-entity camera
                  position="0 1.6 0"
                  look-controls="pointerLockEnabled: true"
				  rotation="0 -90 0"> 
          <a-cursor></a-cursor>
        </a-entity>
      </a-entity>
<?php
/*
      <!-- Teleport gems. -->
      <a-entity id="wall-gem"
                checkpoint="offset: 0 -0.8 0;"
                position="-9 7 -6"
                gltf-model="#gem"
                scale="0.05 0.05 0.05"
                glow="color: #33FF66; intensity: 0.5"
                levitate>
        <a-light not-mobile type="point" intensity="0.5" color="#33FF66" distance="2" position="0.5 -0.25 0"></a-light>
      </a-entity>
      <a-entity id="tower-gem"
                checkpoint="offset: 0 -0.8 0;"
                position="0 10.2 -3.5"
                gltf-model="#gem"
                scale="0.05 0.05 0.05"
                glow="color: #33FF66; intensity: 0.5"
                levitate>
        <a-light not-mobile type="point" intensity="0.5" color="#33FF66" distance="2" position="0.5 -0.25 0"></a-light>
      </a-entity>
*/
?>
      <!-- Nav mesh. -->
      <a-entity nav-mesh
                visible="false"
                position="0 0 20"
                gltf-model="#navmesh"></a-entity>

      <!-- Scene. -->
      <a-entity position="0 0 20"
                scale="1 1 1"
                gltf-model="#hall">
      </a-entity>
    </a-scene>
  </body>
</html>
