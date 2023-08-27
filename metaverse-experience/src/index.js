
require('aframe');
require('aframe-extras');
require('aframe-physics-system');
require('aframe-blink-controls');

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