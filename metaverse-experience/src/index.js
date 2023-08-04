import React from 'react';
import ReactDOM from 'react-dom';
import * as THREE from "three";
import * as AFRAME from "aframe";
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { Canvas, useFrame } from '@react-three/fiber';


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


const controls = new OrbitControls(camera, renderer.domElement);
const loader = new GLTFLoader();
const clock = new THREE.Clock();


const title = 'React with Webpack and Babel';

let greeting = " --- Hallo Welt!---"
console.log(greeting.length)