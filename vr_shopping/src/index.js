import * as THREE from "three";
import * as AFRAME from "aframe";
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
// import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { PhysicsLoader } from "enable3d";
import { AmmoPhysics } from "enable3d/node_modules/@enable3d/ammo-physics";

require('aframe-extras');
require('aframe-blink-controls');

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

const controls = new OrbitControls( camera, renderer.domElement );
const loader = new GLTFLoader();
const clock = new THREE.Clock();

let rooms = [
    roomHall,
];

 const roomNames = [
    'hall',
];


// const urlObject = new URL(window.location);
// const roomName = urlObject.searchParams.get('room');
// context.room = roomNames.indexOf(roomName) !== -1 ? roomNames.indexOf(roomName) : 0;
// console.log(`Current room "${roomNames[context.room]}", ${context.room}`);
// const debug = urlObject.searchParams.has('debug');


document.addEventListener("DOMContentLoaded", function (event) {
    PhysicsLoader('/lib', () => MainScene());
});

let MainScene = function () {
    scene = document.querySelector("a-scene");
    cursor = document.querySelector("a-cursor");
    camera = document.querySelector("#camera");
    wraper = document.querySelector("#wraper");

    physics = new AmmoPhysics(scene.object3D);
    // physics.debug.enable(true);

    let position = wraper.getAttribute("position");

    camera_physics_object = physics.add.sphere(
        { radius: sphere_radius, x: position.x, y: position.y, z: position.z },
        {
            custom: new THREE.MeshLambertMaterial(
                { color: 0xffffff, transparent: true, opacity: 0.5 }
            )
        }
    );

    physics.add.existing(camera_physics_object, { mass: 2, collisionFlags: 1 });

    camera_physics_object.body.setAngularFactor(0, 0, 0);
    camera_physics_object.body.setFriction(0);

    loadGLBWithData();
}

let loadGLBWithData = function () {
    loader.load("models/hall.glb",
        (glb) => {
            scene.object3D.add(glb.scene);
            glb.scene.traverse(function (child) {
                if (child.type == "Mesh") {
                    physics.add.existing(child, {
                        shape: "concave",
                        mass: 0,
                        collisionFlags: 1
                    });
                }
            });
            ready = true;
        }
    );
}