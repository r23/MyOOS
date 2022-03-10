
import * as THREE from 'three'
import React, { Suspense, useRef, useState, useEffect } from 'react'
import { Canvas, useLoader, useFrame, useThree } from '@react-three/fiber'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader'
import { OrthographicCamera, OrbitControls, useAnimations } from '@react-three/drei'
import { GLTFAudioEmitterExtension } from "three-omi";
import { VRCanvas, ARCanvas, DefaultXRControllers, Hands } from '@react-three/xr'
import TeleportTravel from "./TeleportTravel";  

function SavedObject(props) {

  if(props.url){
    const [url, set] = useState(props.url);
    useEffect(() => {
      setTimeout(() => set(props.url), 2000)
    }, []);
    const [listener] = useState(() => new THREE.AudioListener());
    useThree(({camera}) => {
        camera.add(listener);
    });

    const { scene, animations } = useLoader(GLTFLoader, url, (loader) => {
        loader.register(
            (parser) => new GLTFAudioEmitterExtension(parser, listener)
        );
    });

    const { actions } = useAnimations(animations, scene);
    
    const animationList = props.animations ? props.animations.split(',') : "";
    useEffect(() => {
      if(animationList){
        animationList.forEach((name) => {
            if(Object.keys(actions).includes(name)){
                actions[name].play();
            }
        });
      }
    }, []);
    
    scene.position.set(0, props.positionY, 0);
    scene.rotation.set(0, props.rotationY, 0)
    scene.scale.set(props.scale, props.scale, props.scale)
return     <primitive object={scene} />
  } else {
    return null
  }
}
function Floor(props) {
  return (
    <mesh rotation={[-Math.PI / 2, 0, 0]} {...props}>
      <planeBufferGeometry args={[1000, 1000]} attach="geometry" />
      <meshBasicMaterial opacity={0} transparent={true} attach="material"  />
    </mesh>
  );
}
 
export default function ThreeObjectFront(props) {

  if (props.deviceTarget === "vr"){
  return (
    <>
      <VRCanvas shadowMap style={{ backgroundColor: props.backgroundColor, margin: "0 Auto", height: "500px", width: "90%"  }}>
        <TeleportTravel useNormal={true}>
          <Floor rotation={[-Math.PI / 2, 0, 0]} />
        </TeleportTravel>
        <Hands />
        <DefaultXRControllers />
        <OrthographicCamera near={0.1} makeDefault position={[0, 0, 10]} zoom={props.zoom} />
        <ambientLight intensity={0.5} />
          <directionalLight
              intensity={0.6}
              position={[0, 2, 2]}
              shadow-mapSize-width={2048}
              shadow-mapSize-height={2048}
              castShadow
          />
        <Suspense fallback={null}>
          <SavedObject positionY={props.positionY} rotationY={props.rotationY} url={props.threeUrl} color={props.backgroundColor} hasZoom={props.hasZoom} scale={props.scale} hasTip={props.hasTip} animations={props.animations} />
        </Suspense>
        <OrbitControls enableZoom={props.hasZoom === "1" ? true : false}/>
      </VRCanvas>
    { props.hasTip === "1" ? <p className="three-object-block-tip">Click and drag ^</p> : <p></p>}
    </>
    )
  }
  if (props.deviceTarget === "ar"){
    return (
      <>
        <ARCanvas shadowMap style={{ backgroundColor: props.backgroundColor, margin: "0 Auto", height: "500px", width: "90%"  }}>
          <OrthographicCamera near={0.1} makeDefault position={[0, 0, 10]} zoom={props.zoom} />
          <ambientLight intensity={0.5} />
            <directionalLight
                intensity={0.6}
                position={[0, 2, 2]}
                shadow-mapSize-width={2048}
                shadow-mapSize-height={2048}
                castShadow
            />
          <Suspense fallback={null}>
            <SavedObject positionY={props.positionY} rotationY={props.rotationY} url={props.threeUrl} color={props.backgroundColor} hasZoom={props.hasZoom} scale={props.scale} hasTip={props.hasTip} animations={props.animations} />
          </Suspense>
          <OrbitControls enableZoom={props.hasZoom === "1" ? true : false}/>
        </ARCanvas>
      { props.hasTip === "1" ? <p className="three-object-block-tip">Click and drag ^</p> : <p></p>}
      </>
      )  
  }
  if (props.deviceTarget === "2d"){
    return (
      <>
        <Canvas shadowMap style={{ backgroundColor: props.backgroundColor, margin: "0 Auto", height: "500px", width: "90%"  }}>
          <OrthographicCamera near={0.1} makeDefault position={[0, 0, 10]} zoom={props.zoom} />
          <ambientLight intensity={0.5} />
            <directionalLight
                intensity={0.6}
                position={[0, 2, 2]}
                shadow-mapSize-width={2048}
                shadow-mapSize-height={2048}
                castShadow
            />
          <Suspense fallback={null}>
            <SavedObject positionY={props.positionY} rotationY={props.rotationY} url={props.threeUrl} color={props.backgroundColor} hasZoom={props.hasZoom} scale={props.scale} hasTip={props.hasTip} animations={props.animations} />
          </Suspense>
          <OrbitControls enableZoom={props.hasZoom === "1" ? true : false}/>
        </Canvas>
      { props.hasTip === "1" ? <p className="three-object-block-tip">Click and drag ^</p> : <p></p>}
      </>
      )  
  }
}