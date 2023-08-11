// Als Module importieren
import * as THREE from 'three';
import * as AFRAME from 'aframe';

// Aus dem heruntergeladenen Ordner importieren
// Aus dem Three.js-Paket importieren
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { PointerLockControls } from 'three/examples/jsm/controls/PointerLockControls';


// import { Canvas, useFrame } from '@react-three/fiber';

require('aframe-extras');
require('aframe-physics-system');

var el = this.el; // The entity to which this component is attached.
var scene = el.sceneEl; // The A-Frame Scene

// var el = document.querySelector('#renderer'); // access to the entity
var renderer = el.components.renderer.renderer; // access the renderer
renderer.xr.setSessionMode( 'immersive-vr' ); // activates the VR mode

var threeScene = scene.getObject3D('scene'); // Die Three.js-Szene

// Erstellt eine neue Three.js-Kamera
// var camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 ); 

// Zugriff auf die A-Frame-Kamera
var camera = document.querySelector('#camera').getObject3D('camera');

var controls = new PointerLockControls( camera, document.body );
threeScene.add( controls.getObject() ) // fügt die Steuerung der Szene hinzu


function animate() {
  renderer.render( scene, camera ); // Rendert die Szene
}

// verwendet requestAnimationFrame, um die animate-Funktion aufzurufen
function loop() {
  requestAnimationFrame( loop ); // Ruft die loop-Funktion wieder auf
  animate(); // Ruft die animate-Funktion auf
}

loop(); // Startet die loop-Funktion