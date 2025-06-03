@extends('dashboard.home')

@section('content')
<div class="container py-5">
    <div class="card shadow rounded-4 mx-auto" style="max-width: 420px; background: #f8f9fa;">
        <div class="card-body text-center">
            <h2 class="mb-4" style="font-family: 'Inter', 'Segoe UI', Arial, sans-serif; font-weight: 700;">
                Upload & Crop Foto Profil
            </h2>
            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif

            @php
                $profilePhotoPath = Auth::user()->profilePhoto 
                    ? asset('storage/' . Auth::user()->profilePhoto->photo_path) 
                    : asset('assets/images/users/profile-pic.jpg');
            @endphp

            <div class="position-relative d-inline-block mb-3 avatar-hover-group" style="transition: box-shadow .3s;">
                <img id="profile-photo" src="{{ $profilePhotoPath }}"
                    alt="Foto Profil"
                    class="rounded-circle shadow avatar-main-img"
                    style="width: 170px; height: 170px; object-fit: cover; box-shadow: 0 4px 16px rgba(13,110,253,0.12); transition: box-shadow .3s;">
                <button type="button" 
                        class="btn btn-light shadow edit-btn position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 44px; height: 44px; border: 2.5px solid #f8f9fa; box-shadow: 0 2px 8px rgba(13,110,253,0.16); font-size: 1.5rem; background: #fff; transition: box-shadow .2s;"
                        data-bs-toggle="modal" data-bs-target="#uploadChoiceModal"
                        aria-label="Edit Foto Profil">
                    <i class="bi bi-pencil-fill" style="color: #0d6efd;"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilihan Upload -->
<div class="modal fade neumorph-modal" id="uploadChoiceModal" tabindex="-1" aria-labelledby="uploadChoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-2" style="background: #f8f9fa; border: none; box-shadow: 0 8px 32px rgba(13,110,253,0.08);">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title" style="font-family: 'Inter', sans-serif;">Pilih Metode Upload</h5>
                <button type="button" class="btn-close rounded-circle" style="background: #e9ecef;" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-around gap-3 pt-1 pb-3">
                <button id="chooseFileBtn" class="btn btn-neumorph d-flex flex-column align-items-center gap-1 px-4 py-3 rounded-3 shadow-sm">
                    <span style="font-size:2rem;"><i class="bi bi-upload"></i></span>
                    <span style="font-size:.97rem; font-weight:500;">Upload dari File</span>
                </button>
                <button id="takePhotoBtn" class="btn btn-neumorph d-flex flex-column align-items-center gap-1 px-4 py-3 rounded-3 shadow-sm">
                    <span style="font-size:2rem;"><i class="bi bi-camera"></i></span>
                    <span style="font-size:.97rem; font-weight:500;">Ambil dari Kamera</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kamera -->
<div class="modal fade neumorph-modal" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4" style="background: #f8f9fa; border: none; box-shadow: 0 8px 32px rgba(13,110,253,0.08);">
            <div class="modal-header border-0">
                <h5 class="modal-title">Ambil Foto</h5>
                <button type="button" class="btn-close rounded-circle" style="background: #e9ecef;" data-bs-dismiss="modal" id="closeCameraModal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <video id="video" autoplay playsinline style="width: 100%; border-radius: 1rem; background: #000; max-height:340px;"></video>
                <canvas id="canvas" style="display:none;"></canvas>
                <button id="captureBtn" class="btn btn-primary mt-3 rounded-3 px-4" style="box-shadow: 0 2px 8px rgba(13,110,253,0.10); font-weight: 600;">Ambil Foto</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crop Gambar -->
<div class="modal fade neumorph-modal" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4" style="background: #f8f9fa; border: none; box-shadow: 0 8px 32px rgba(13,110,253,0.10);">
            <div class="modal-header border-0">
                <h5 class="modal-title">Crop Foto</h5>
                <button type="button" class="btn-close rounded-circle" style="background: #e9ecef;" data-bs-dismiss="modal" id="closeCropModal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div style="max-width: 100%; max-height: 400px;">
                    <img id="image-to-crop" src="" style="max-width: 100%; border-radius: 0;">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" id="cropAndUploadBtn" style="font-weight: 600;">Crop & Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Form Upload (disembunyikan) -->
<form action="{{ route('profile_photo.store') }}" method="POST" enctype="multipart/form-data" id="upload-form" style="display:none;">
    @csrf
    <input type="file" name="photo" id="photo-upload" accept="image/*">
    <input type="hidden" name="photo_data" id="photo-data-input">
</form>

<!-- Tombol Kembali -->
<a href="{{ session('previous_url', url()->previous()) }}" 
   class="btn btn-outline-secondary position-fixed shadow rounded-3"
   style="bottom: 20px; right: 20px; z-index: 1050; width: 120px; background: #fff; border: 1.5px solid #dee2e6; font-weight: 500;">
   ← Kembali
</a>

<!-- Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<!-- Bootstrap Icons (for edit/camera/upload icons) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<!-- Google Fonts (Inter) -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* Neumorphism style */
.btn-neumorph {
    background: #f8f9fa;
    border: none;
    box-shadow: 4px 4px 12px #e2e6ea, -4px -4px 12px #fff;
    color: #0d6efd;
    font-weight: 600;
    transition: box-shadow .18s, background .18s;
}
.btn-neumorph:hover, .btn-neumorph:focus {
    box-shadow: 2px 2px 8px #e2e6ea, -2px -2px 8px #fff, 0 0 0 2px #0d6efd33;
    background: #e9ecef;
    color: #0a58ca;
}

.avatar-main-img {
    transition: box-shadow .3s, transform .2s;
}
.avatar-main-img:hover, .avatar-hover-group:hover .avatar-main-img {
    box-shadow: 0 6px 28px 0 #0d6efd40;
    transform: scale(1.03);
}

.edit-btn {
    background: #f8f9fa;
    color: #0d6efd;
    border: 2.5px solid #f8f9fa;
    transition: box-shadow .18s, background .18s;
}
.edit-btn:hover, .edit-btn:focus {
    background: #e9ecef;
    box-shadow: 0 2px 8px #0d6efd33;
    color: #084298;
}

.neumorph-modal .modal-dialog {
    animation: fadeInUpModal .32s cubic-bezier(.44,1.12,.82,1.02);
}
@keyframes fadeInUpModal {
    from { transform: translateY(60px) scale(.94); opacity: 0; }
    to { transform: none; opacity: 1; }
}
@media (max-width: 600px) {
    .card { max-width: 98vw !important; }
    .avatar-main-img { width: 112px !important; height: 112px !important; }
    .edit-btn { width: 36px !important; height: 36px !important; font-size: 1.1rem !important; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chooseFileBtn = document.getElementById('chooseFileBtn');
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    const photoUploadInput = document.getElementById('photo-upload');
    const photoDataInput = document.getElementById('photo-data-input');
    const profilePhoto = document.getElementById('profile-photo');
    const uploadForm = document.getElementById('upload-form');
    const imageToCrop = document.getElementById('image-to-crop');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
    let cropper;

    // Pilih Upload dari File
    chooseFileBtn.addEventListener('click', () => {
        bootstrap.Modal.getInstance(document.getElementById('uploadChoiceModal')).hide();
        setTimeout(() => photoUploadInput.click(), 250);
    });

    // Setelah file dipilih, tampilkan crop modal
    photoUploadInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (event) => {
            imageToCrop.onload = () => {
                cropModal.show();
            };
            imageToCrop.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });

    // Pilih Ambil Foto lewat kamera
    takePhotoBtn.addEventListener('click', () => {
        bootstrap.Modal.getInstance(document.getElementById('uploadChoiceModal')).hide();
        setTimeout(() => {
            const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'));
            cameraModal.show();

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    const video = document.getElementById('video');
                    video.srcObject = stream;
                })
                .catch(err => alert('Gagal akses kamera: ' + err.message));
        }, 250);
    });

    // Tombol Ambil Foto dari video
    document.getElementById('captureBtn').addEventListener('click', () => {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        const dataURL = canvas.toDataURL('image/png');
        imageToCrop.src = dataURL;

        // Hentikan stream kamera setelah foto diambil
        const stream = video.srcObject;
        stream.getTracks().forEach(track => track.stop());

        bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();
        setTimeout(() => cropModal.show(), 250);
    });

    // Inisialisasi Cropper saat modal crop terbuka
    document.getElementById('cropModal').addEventListener('shown.bs.modal', () => {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            background: false,
            guides: false,
            cropBoxResizable: false,
            cropBoxMovable: false,
            ready() {
                const cropBox = document.querySelector('.cropper-crop-box');
                const face = document.querySelector('.cropper-face');
                if(cropBox) cropBox.style.borderRadius = '50%';
                if(face) face.style.borderRadius = '50%';
            }
        });
    });

    // Hancurkan cropper saat modal crop ditutup
    document.getElementById('cropModal').addEventListener('hidden.bs.modal', () => {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    // Tombol Crop & Upload
    document.getElementById('cropAndUploadBtn').addEventListener('click', () => {
        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        const roundedCanvas = getRoundedCanvas(canvas);
        const dataURL = roundedCanvas.toDataURL('image/png');

        // Update preview foto profil dengan animasi fade
        profilePhoto.style.opacity = '0.3';
        setTimeout(() => {
            profilePhoto.src = dataURL;
            profilePhoto.style.opacity = '1';
        }, 180);

        // Simpan data base64 hasil crop ke input tersembunyi
        photoDataInput.value = dataURL;

        cropModal.hide();

        // Submit form (mengirimkan foto)
        setTimeout(() => uploadForm.submit(), 300);
    });

    // Fungsi untuk membuat canvas bundar (rounded)
    function getRoundedCanvas(sourceCanvas) {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        const width = sourceCanvas.width;
        const height = sourceCanvas.height;
        canvas.width = width;
        canvas.height = height;

        context.beginPath();
        context.arc(width / 2, height / 2, width / 2, 0, Math.PI * 2, true);
        context.closePath();
        context.clip();

        context.drawImage(sourceCanvas, 0, 0, width, height);

        return canvas;
    }

    // Smooth modal animation when modals shown/hidden
    document.querySelectorAll('.neumorph-modal').forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            this.style.opacity = 0;
            setTimeout(() => this.style.opacity = 1, 10);
        });
        modal.addEventListener('hide.bs.modal', function() {
            this.style.opacity = 0.4;
        });
    });
});
</script>
@endsection