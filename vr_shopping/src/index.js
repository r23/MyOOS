import * as THREE from "three";
import * as AFRAME from "aframe";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { PhysicsLoader } from "enable3d";
import { AmmoPhysics } from "enable3d/node_modules/@enable3d/ammo-physics";

require('aframe-extras');

let scene;
let wraper;
let camera;
let cursor;

let loader = new GLTFLoader();
let clock = new THREE.Clock();
