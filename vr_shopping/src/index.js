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

let gamepad;

let physics;
let camera_physics_object;

let sphere_radius = 0.5;
let camera_height = 1;
let ready = false;

let loader = new GLTFLoader();
let clock = new THREE.Clock();

document.addEventListener("DOMContentLoaded", function (event) {
    PhysicsLoader('/lib', () => MainScene());
});