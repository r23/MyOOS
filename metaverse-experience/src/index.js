import * as THREE from 'three';
import * as AFRAME from 'aframe';


import 'aframe-extras/dist/aframe-extras.loaders.min.js'; 
import 'aframe-physics-system/dist/aframe-physics-system.min.js';

import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { PointerLockControls } from 'three/examples/jsm/controls/PointerLockControls';


require('aframe-blink-controls');

// var el = this.el; // The entity to which this component is attached.
// var scene = el.sceneEl; // The A-Frame Scene

// var el = document.querySelector('#renderer'); // access to the entity
// var renderer = el.components.renderer.renderer; // access the renderer
// renderer.xr.setSessionMode( 'immersive-vr' ); // activates the VR mode

// var threeScene = scene.getObject3D('scene'); // Die Three.js-Szene

// Erstellt eine neue Three.js-Kamera
// var camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 ); 

// Zugriff auf die A-Frame-Kamera
// var camera = document.querySelector('#camera').getObject3D('camera');

// var controls = new PointerLockControls( camera, document.body );
// threeScene.add( controls.getObject() ) // fügt die Steuerung der Szene hinzu

    // On click, send the NPC to the target location.
    AFRAME.registerComponent('nav-pointer', {
        init: function () {
          const el = this.el;
      
          // On click, send the NPC to the target location.
          el.addEventListener('click', (e) => {
            const ctrlEl = el.sceneEl.querySelector('[nav-agent]');
            ctrlEl.setAttribute('nav-agent', {
              active: true,
              destination: e.detail.intersection.point
            });
          });
      
          // When hovering on the nav mesh, show a green cursor.
          el.addEventListener('mouseenter', () => {
            el.setAttribute('material', {color: 'green'});
          });
          el.addEventListener('mouseleave', () => {
            el.setAttribute('material', {color: 'crimson'})
          });
      
          // Refresh the raycaster after models load.
          el.sceneEl.addEventListener('object3dset', () => {
            this.el.components.raycaster.refreshObjects();
          });
        }
      });