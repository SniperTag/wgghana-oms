<div class="text-center">
    <h2 class="text-xl font-bold mb-4">Face Enrollment</h2>

    @if (session()->has('success'))
        <div class="bg-green-200 text-green-900 p-2 mb-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <video id="video" width="320" height="240" autoplay class="mx-auto border rounded"></video>
    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>

    <button id="capture" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">📸 Capture Face</button>

    <div class="mt-4">
        @if ($faceImage)
            <p class="text-sm mb-2">Captured Face:</p>
            <img src="{{ $faceImage }}" class="mx-auto rounded border" width="200" />
        @endif
    </div>

    <button wire:click="saveFaceImage"
            class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            @if (!$faceImage) disabled @endif>
        ✅ Submit Face
    </button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => video.srcObject = stream)
        .catch(err => alert('⚠️ Unable to access camera: ' + err.message));

    captureButton.addEventListener('click', function () {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const base64Image = canvas.toDataURL('image/png');
        @this.set('faceImage', base64Image);
    });
});
</script>
