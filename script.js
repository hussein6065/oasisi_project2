const video = document.getElementById('video');

console.log('I am in bro, Hussein');
Promise.all([
	faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
	faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
	faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
]).then(start);

function startVideo() {
	navigator.getUserMedia(
		{ video: {} },
		(stream) => {
			video.srcObject = stream;
		},
		(err) => console.error(err)
	);
}

console.log('I am in br **2, Hussein');
async function start() {
	await startVideo();
	const container = document.createElement('div');
	container.style.position = 'relative';
	document.body.append(container);

	const labeledFaceDescriptors = await loadLabeledImages();
	// // console.log('This is who I got from the function, ', labeledFaceDescriptors);
	const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

	const canvas = faceapi.createCanvasFromMedia(video);
	document.body.append(canvas);

	const displaySize = { width: video.width, height: video.height };
	faceapi.matchDimensions(canvas, displaySize);

	setInterval(async () => {
		const detections = await faceapi
			.detectAllFaces(video)
			.withFaceLandmarks()
			.withFaceDescriptors();
		const resizedDetections = faceapi.resizeResults(detections, displaySize);
		const results = resizedDetections.map((d) =>
			faceMatcher.findBestMatch(d.descriptor)
		);

		results.length > 0
			? console.log((await getName(results[0]._label)).name)
			: console.log('failed');
		// console.log();
		// console.log(results);
	}, 100);
}

async function loadLabeledImages() {
	let facultyIds;
	await fetch(
		'http://localhost/Oasis_Second_Project/Backend/api/getFacultyIds.php'
	)
		.then((response) => response.json())
		.then((data) => {
			facultyIds = data.data;
		});

	// Get the ids of the lectures.

	// console.log(facultyIds);
	return Promise.all(
		facultyIds.map(async (facultyId) => {
			const descriptions = [];
			for (let i = 1; i <= 2; i++) {
				const img = await faceapi.fetchImage(`./images/${facultyId}/${i}.jpeg`);
				const detections = await faceapi
					.detectSingleFace(img)
					.withFaceLandmarks()
					.withFaceDescriptor();
				descriptions.push(detections.descriptor);
			}
			return new faceapi.LabeledFaceDescriptors(facultyId, descriptions);
		})
	);
}

async function getName(id) {
	let result;
	await fetch('http://localhost/Oasis_Second_Project/Backend/api/getName.php', {
		method: 'POST',
		body: JSON.stringify({ id: id }),
	})
		.then((response) => response.json())
		.then((data) => (result = data));

	return result;
}
