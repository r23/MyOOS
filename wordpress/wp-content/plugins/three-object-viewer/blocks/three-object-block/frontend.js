const { Component, render } = wp.element;

import ThreeObjectFront from "./components/ThreeObjectFront";

const threeApp = document.querySelectorAll(".three-object-three-app");

threeApp.forEach((threeApp) => {
	if (threeApp) {
		const threeUrl = threeApp.querySelector("p.three-object-block-url")
			? threeApp.querySelector("p.three-object-block-url").innerText
			: "";
		const deviceTarget = threeApp.querySelector(
			"p.three-object-block-device-target"
		)
			? threeApp.querySelector("p.three-object-block-device-target")
					.innerText
			: "2D";
		const backgroundColor = threeApp.querySelector(
			"p.three-object-background-color"
		)
			? threeApp.querySelector("p.three-object-background-color")
					.innerText
			: "#ffffff";
		const zoom = threeApp.querySelector("p.three-object-zoom")
			? threeApp.querySelector("p.three-object-zoom").innerText
			: 90;
		const scale = threeApp.querySelector("p.three-object-scale")
			? threeApp.querySelector("p.three-object-scale").innerText
			: 1;
		const hasZoom = threeApp.querySelector("p.three-object-has-zoom")
			? threeApp.querySelector("p.three-object-has-zoom").innerText
			: false;
		const hasTip = threeApp.querySelector("p.three-object-has-tip")
			? threeApp.querySelector("p.three-object-has-tip").innerText
			: true;
		const positionY = threeApp.querySelector("p.three-object-position-y")
			? threeApp.querySelector("p.three-object-position-y").innerText
			: 0;
		const rotationY = threeApp.querySelector("p.three-object-rotation-y")
			? threeApp.querySelector("p.three-object-rotation-y").innerText
			: 0;
		const animations = threeApp.querySelector("p.three-object-animations")
			? threeApp.querySelector("p.three-object-animations").innerText
			: "";

		render(
			<ThreeObjectFront
				threeObjectPlugin={threeObjectPlugin}
				defaultAvatarAnimation={defaultAvatarAnimation}
				threeUrl={threeUrl}
				deviceTarget={deviceTarget}
				zoom={zoom}
				scale={scale}
				hasTip={hasTip}
				hasZoom={hasZoom}
				positionY={positionY}
				rotationY={rotationY}
				animations={animations}
				backgroundColor={backgroundColor}
			/>,
			threeApp
		);
	}
});
