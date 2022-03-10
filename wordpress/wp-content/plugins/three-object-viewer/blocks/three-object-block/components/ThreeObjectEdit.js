import * as THREE from 'three';
import React, { Suspense, useRef, useState, useEffect } from 'react';
import { Canvas, useLoader, useFrame, useThree } from '@react-three/fiber';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { OrthographicCamera, OrbitControls, useAnimations } from '@react-three/drei';
import { GLTFAudioEmitterExtension } from "three-omi";

function ThreeObject(props) {
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
            // actions["Armature|Take 001|BaseLayer"].play();
            if(animationList){
                animationList.forEach((name) => {
                    if(Object.keys(actions).includes(name)){
                        actions[name].play();
                    }
                });
            }
        }, []);
        
        scene.position.set(props.positionX, props.positionY, 0);
        scene.rotation.set(0, props.rotationY, 0)
        scene.scale.set(props.scale, props.scale, props.scale)
        return <primitive object={scene} />
    } else { return null }
}

export default function ThreeObjectEdit(props) {
  return (
    <>
    <Canvas shadowMap style={{ backgroundColor: props.backgroundColor, margin: "0 Auto", height: "500px", width: "90%" }}>
        <OrthographicCamera near={0} makeDefault position={[0, 0, 10]} zoom={props.zoom} />
        <ambientLight intensity={0.5} />
        <directionalLight
            intensity={0.6}
            position={[0, 2, 2]}
            shadow-mapSize-width={2048}
            shadow-mapSize-height={2048}
            castShadow
        />
        <Suspense fallback={null}>
            <ThreeObject url={props.url} positionX={props.positionX} positionY={props.positionY} rotationY={props.rotationY} scale={props.scale} animations={props.animations}/>
        </Suspense>
        <OrbitControls enableZoom={props.hasZoom}/>
    </Canvas>
    { props.hasTip &&
        <p className="three-object-block-tip">Click and drag ^</p>
        }
    </>
    )
}