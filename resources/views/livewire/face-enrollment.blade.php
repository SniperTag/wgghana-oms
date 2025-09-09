<div>
    <video id="video" autoplay muted playsinline width="320" height="240" class="border rounded mb-2"></video>
    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
    <img id="preview" style="display:none; max-width:100%; border:1px solid #ccc; margin-top:1rem;" />

    <button id="capture-btn" class="bg-blue-600 text-white px-4 py-2 rounded mt-2 hover:bg-blue-700">
        Capture Face
    </button>

    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-md text-center max-w-sm mx-auto">
            <h3 class="text-lg font-semibold mb-4">Confirm Face Enrollment</h3>
            <p>Do you want to save this captured face?</p>
            <div class="mt-4 flex justify-center space-x-4">
                <button id="confirmYes" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Yes</button>
                <button id="confirmNo" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">No</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:load', function () {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');
    const captureBtn = document.getElementById('capture-btn');
    const modal = document.getElementById('confirmModal');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    let dataUrl = null;

    // Start webcam
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => { video.srcObject = stream; })
        .catch(err => alert('Camera error: ' + err));

    captureBtn.addEventListener('click', () => {
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        dataUrl = canvas.toDataURL('image/jpeg', 0.7); // compressed JPEG
        preview.src = dataUrl;
        preview.style.display = 'block';

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
});
</script>
