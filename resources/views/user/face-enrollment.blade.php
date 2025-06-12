<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.app')
    <title>Face Enrollment</title>
    @livewireStyles
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        canvas {
            display: none;
        }

        #preview {
            margin-top: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        
        @livewire('face-enrollment')

        @livewireScripts
        {{--  <script>
            document.addEventListener("DOMContentLoaded", function() {
                const video = document.createElement("video");
                video.setAttribute("autoplay", true);
                video.setAttribute("playsinline", true);
                video.style.width = "100%";
                video.style.maxWidth = "320px";
                document.querySelector('.container').prepend(video);

                // Try accessing the webcam
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(stream => {
                        video.srcObject = stream;
                    })
                    .catch(error => {
                        alert("⚠️ Unable to access webcam: " + error.message);
                        console.error("Webcam error:", error);
                    });

                // Listen for the capture button (if any)
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'capture-face') {
                        const canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(video, 0, 0);

                        const imageData = canvas.toDataURL('image/png');
                        Livewire.emit('imageCaptured', imageData);
                    }
                });
            });
        </script>  --}}


        <!-- Modal -->
        <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-md text-center max-w-sm mx-auto">
                <h3 class="text-lg font-semibold mb-4">Confirm Face Enrollment</h3>
                <p>Do you want to save this captured face?</p>
                <div class="mt-4 flex justify-center space-x-4">
                    <button id="confirmYes"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Yes</button>
                    <button id="confirmNo" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">No</button>
                </div>
            </div>
        </div>

        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const preview = document.getElementById('preview');
            const captureBtn = document.getElementById('capture-btn');
            const modal = document.getElementById('confirmModal');
            const confirmYes = document.getElementById('confirmYes');
            const confirmNo = document.getElementById('confirmNo');

            // Load face-api models
            Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models')
            ]).then(startVideo);

            function startVideo() {
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(stream => {
                        video.srcObject = stream;
                    })
                    .catch(err => alert('Camera error: ' + err));
            }

            let dataUrl = null;

            captureBtn.addEventListener('click', async () => {
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions());

                if (detections.length === 0) {
                    alert('❌ No face detected. Please try again.');
                    return;
                }

                if (detections.length > 1) {
                    alert('⚠️ Multiple faces detected. Please ensure only your face is visible.');
                    return;
                }

                const {
                    x,
                    y,
                    width,
                    height
                } = detections[0].box;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, x, y, width, height, 0, 0, 320, 240);
                dataUrl = canvas.toDataURL('image/png');

                preview.src = dataUrl;
                preview.style.display = 'block';

                // Show confirmation modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            confirmYes.addEventListener('click', () => {
                Livewire.emit('imageCaptured', dataUrl);
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            confirmNo.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        </script>

    </div>
</body>

</html>
