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


AFRAME.registerComponent("scene_update", {
    tick: function () {
        scene_custom_update();
    }
});

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

let scene_custom_update = function () {
    gamepad = navigator.getGamepads()[0];
    if (ready && gamepad) {
        movement();
        desktop_rotation();
        action_button();
        physics.update(clock.getDelta() * 1000);
        // physics.updateDebugger();
    }
}

let movement = function () {
    let camera_direction = new THREE.Vector3();
    camera.object3D.getWorldDirection(camera_direction);

    camera_direction.y = 0;
    camera_direction.normalize();

    let side_direction = camera_direction.clone().applyAxisAngle(new THREE.Vector3(0, 1, 0), Math.PI / 2);

    let move = new THREE.Vector3(0, 0, 0);

    if (Math.abs(gamepad.axes[0]) > 0.5) {
        move.x += (side_direction.x * gamepad.axes[0]);
        move.z += (side_direction.z * gamepad.axes[0]);
    }

    if (Math.abs(gamepad.axes[1]) > 0.5) {
        move.x += (camera_direction.x * gamepad.axes[1]);
        move.z += (camera_direction.z * gamepad.axes[1]);
    }

    move.normalize();

    camera_physics_object.body.setVelocityX(move.x * 2);
    camera_physics_object.body.setVelocityZ(move.z * 2);

    if (camera_physics_object.body.velocity.y > 1) {
        camera_physics_object.body.setVelocityY(1);
    }

    wraper.object3D.position.set(
        camera_physics_object.position.x,
        camera_physics_object.position.y + camera_height,
        camera_physics_object.position.z
    );
}

let desktop_rotation = function(){
    if (Math.abs(gamepad.axes[2]) == 1) {
        camera.components["look-controls"].yawObject.rotation.y -= (Math.PI / 100) * gamepad.axes[2];
    }
    if (Math.abs(gamepad.axes[3]) == 1) {
        camera.components["look-controls"].pitchObject.rotation.x += (Math.PI / 100) * gamepad.axes[3];
    }
}

let main_button = {
    value: 0
}

let main_button_handler = {
    set: function (target, key, value) {
        if (value == 1) {
            action();
        }
        target[key] = value;
        return true;
    }
}

let main_button_proxy = new Proxy(main_button, main_button_handler);

let action = function () {
    let raycaster = cursor.components.raycaster.raycaster;
    let intersect = raycaster.intersectObjects(scene.object3D.children);
    insert_text_panel(intersect[0].object.parent.userData.MyText + "");
}

let insert_text_panel = function (text) {
    let text_panel = document.createElement("a-entity");

    text_panel.setAttribute("geometry", { "primitive": "plane", "height": "auto", "width": "auto" });
    text_panel.setAttribute("material", { "color": "#21130d" });
    text_panel.setAttribute("text", { "value": text, "width": "auto", "height": "auto", "align": "center", "color": "#e9e7e7" });

    let world_position = new THREE.Vector3();
    camera.object3D.getWorldPosition(world_position);

    let world_direction = new THREE.Vector3();
    camera.object3D.getWorldDirection(world_direction);

    let add_position = world_direction.clone();
    add_position.multiply(new THREE.Vector3(-1, -1, -1));

    world_position.add(add_position);

    text_panel.setAttribute("position", world_position.x + " " + world_position.y + " " + (world_position.z));

    let rotation = camera.object3D.rotation.clone();

    text_panel.setAttribute("rotation", (rotation.x * 180 / Math.PI) + " " + (rotation.y * 180 / Math.PI) + " " + (rotation.z * 180 / Math.PI));

    scene.appendChild(text_panel);

    setTimeout(() => {
        text_panel.remove();
    }, 4000);
}

let action_button = function(){
    if (main_button.value != gamepad.buttons[0].value) {
        main_button_proxy.value = gamepad.buttons[0].value;
    }
}

