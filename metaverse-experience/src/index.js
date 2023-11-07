import * as AFRAME from 'aframe';

require('aframe-extras');
require('aframe-blink-controls');
require('super-hands');
require('@c-frame/aframe-physics-system');

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


/**
 * Basic emissive effect.
 */
AFRAME.registerComponent('glow', {
  schema: {
    color: {default: '#ffffff', type: 'color'},
    intensity: {default: 1.0}
  },
  init: function () {
    this.el.addEventListener('object3dset', function () {
      this.update();
    }.bind(this));
  },
  update: function () {
    var data = this.data;
    this.el.object3D.traverse(function (node) {
      if (node.isMesh) {
        node.material.emissive.copy(new THREE.Color(data.color));
        node.material.emissiveIntensity = data.intensity;
      }
    });
  }
});

/**
 * Simple spin-and-levitate animation.
 */
AFRAME.registerComponent('levitate', {
  tick: function (t, dt) {
    var mesh = this.el.getObject3D('mesh');
    if (!mesh) return;
    mesh.rotation.y += 0.1 * dt / 1000;
    mesh.position.y = 0.25 * Math.sin(t / 1000);
  }
});

/**
 * Removes current element if on a mobile device.
 */
AFRAME.registerComponent('not-mobile',  {
  init: function () {
    var el = this.el;
    if (el.sceneEl.isMobile) {
      el.parentEl.remove(el);
    }
  }
});

/**
 * Video
 */
AFRAME.registerComponent('play-pause', {
  init: function () {
    var myVideo = document.querySelector('#my-video');
    var videoControls = document.querySelector('#videoControls');
    this.el.addEventListener('click', function () {
      if (myVideo.paused) {
        myVideo.play();
        videoControls.setAttribute('src', '#pause');
      } else {
        myVideo.pause();
        videoControls.setAttribute('src', '#play');
      }
    });
  }
});

/*
// Create a component that renders a three.js scene
function ThreeScene() {
  // Import react-three-fiber
  const { useFrame } = require("react-three-fiber");

  // Create a ref for the mesh
  const meshRef = React.useRef();

  // Animate the mesh on each frame
  useFrame(() => {
    meshRef.current.rotation.x += 0.01;
    meshRef.current.rotation.y += 0.01;
  });

  // Render the canvas element with some three.js objects
  return (
    <Canvas>
      <ambientLight />
      <pointLight position={[10, 10, 10]} />
      <mesh ref={meshRef}>
        <boxBufferGeometry args={[1, 1, 1]} />
        <meshStandardMaterial color={"orange"} />
      </mesh>
    </Canvas>
  );
}

// Register the component with aframe-react
AFRAME.registerComponent("three-scene", {
  init: function () {
    // Render the component to this element
    render(<ThreeScene />, this.el);
  },
});

// Render the scene with the three-scene component
render(
  <Scene>
    <a-entity three-scene></a-entity>
  </Scene>,
  document.body
);

*/