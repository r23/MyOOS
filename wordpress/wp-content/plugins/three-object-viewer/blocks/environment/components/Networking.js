import P2PCF from "./p2pcf/p2pcf.js";

const Networking = (props) => {
	if (!document.location.hash) {
		document.location =
			document.location.toString() + `#xpp-${props.postSlug}`;
	}

	const userProfileName =
		userData.userId === ""
			? Math.floor(Math.random() * 100000)
			: userData.userId;
	const p2pcf = new P2PCF(
		"user-" + userProfileName,
		document.location.hash.substring(1),
		{
			workerUrl: "https://p2pcf.sxpdigital.workers.dev/",
			slowPollingRateMs: 5000,
			fastPollingRateMs: 1500
		}
	);
	window.p2pcf = p2pcf;
	console.log("client id:", p2pcf.clientId);

	const removePeerUi = (clientId) => {
		document.getElementById(clientId)?.remove();
		document.getElementById(`${clientId}-video`)?.remove();
	};

	const addPeerUi = (sessionId) => {
		if (document.getElementById(sessionId)) return;

		const peerEl = document.createElement("div");
		peerEl.style = "display: flex;";

		const name = document.createElement("div");
		name.innerText = sessionId.substring(0, 5);

		peerEl.id = sessionId;
		peerEl.appendChild(name);

		document.getElementById("peers").appendChild(peerEl);
	};
	const addMessage = (message) => {
		const messageEl = document.createElement("div");
		messageEl.innerText = message;

		document.getElementById("messages").appendChild(messageEl);
	};
	let stream;
	p2pcf.on("peerconnect", (peer) => {
		console.log("Peer connect", peer.id, peer);
		console.log(peer.client_id);

		if (stream) {
			peer.addStream(stream);
		}
		peer.on("track", (track, stream) => {
			console.log("got track", track);
			const video = document.createElement("audio");
			video.id = `${peer.id}-audio`;
			video.srcObject = stream;
			video.setAttribute("playsinline", true);
			document.getElementById("videos").appendChild(video);
			video.play();
		});
		addPeerUi(peer.id);
	});

	p2pcf.on("peerclose", (peer) => {
		console.log("Peer close", peer.id, peer);
		removePeerUi(peer.id);
	});

	p2pcf.on("msg", (peer, data) => {
		addMessage(
			peer.id.substring(0, 5) +
				": " +
				new TextDecoder("utf-8").decode(data)
		);
	});

	const go = () => {
		document.getElementById("session-id").innerText =
			p2pcf.sessionId.substring(0, 5) + "@" + p2pcf.roomId + ":";

		// document.getElementById('send-button').addEventListener('click', () => {
		//     const box = document.getElementById('send-box');
		//     addMessage(p2pcf.sessionId.substring(0, 5) + ': ' + box.value);
		//     p2pcf.broadcast(new TextEncoder().encode(box.value));
		//     box.value = '';
		// })

		document
			.getElementById("audio-button")
			.addEventListener("click", async () => {
				stream = await navigator.mediaDevices.getUserMedia({
					audio: true
				});

				for (const peer of p2pcf.peers.values()) {
					peer.addStream(stream);
				}
			});

		p2pcf.start();
	};
	if (
		document.readyState === "complete" ||
		document.readyState === "interactive"
	) {
		document
			.getElementById("join-button")
			.addEventListener("click", async () => {
				window.addEventListener("DOMContentLoaded", audio, {
					once: true
				});
				// window.addEventListener('DOMContentLoaded', go, { once: true })
			});
	} else {
		window.addEventListener("DOMContentLoaded", go, { once: true });
	}

	return <></>;
};

export default Networking;