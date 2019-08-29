

        <example-snippet stamp-to="demo-container-1" highlight-as="html">
          <template>
<model-viewer src="assets/Astronaut.glb" ar camera-controls alt="A 3D model of an astronaut" background-color="#222" ios-src="assets/Astronaut.usdz" magic-leap unstable-webxr></model-viewer>
          </template>
        </example-snippet>



  <div class="footer">
    <ul>
      <li class="attribution">
        <a href="https://poly.google.com/view/dLHpzNdygsg">Astronaut</a> by <a href="https://poly.google.com/user/4aEd8rQgKu2">Poly</a>,
        licensed under <a href="https://creativecommons.org/licenses/by/2.0/">CC-BY</a>.
      </li>
    </ul>
  </div>

 <!-- Loads <model-viewer> only on modern browsers: -->
  <script type="module"
      src="../dist/model-viewer.js">
  </script>

  <!-- Loads <model-viewer> only on old browsers like IE11: -->
  <script nomodule
      src="../dist/model-viewer-legacy.js">
  </script>

