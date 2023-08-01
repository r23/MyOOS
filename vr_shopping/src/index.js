import * as THREE from "three";
import * as AFRAME from "aframe";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";

require('aframe-extras');

let scene;
let wraper;
let camera;
let cursor;

let loader = new GLTFLoader();
let clock = new THREE.Clock();
