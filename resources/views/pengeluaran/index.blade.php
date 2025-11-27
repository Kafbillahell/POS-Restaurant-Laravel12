@extends('dashboard.home') {{-- Menggunakan master layout Anda --}}

@section('styles')
{{-- CSS TEMA MONOCHROME yang Anda berikan. Masukkan di sini jika belum ada di dashboard.home --}}
<style>
/* ---------------------------------------------------- */
/* A. TEMA: Minimalist Monochrome SOFT Modern */
/* ---------------------------------------------------- */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
    --black: #171717;
    --gray-dark: #333333;
    --gray-medium: #6c757d;
    --gray-light: #f5f5f5;
    --bg-soft: #ffffff; 
    /* Warna tambahan untuk PENGELUARAN (Merah/Gagal) */
    --expense-red: #dc3545;
    --expense-light: #fbe6e8;
}

body {
    background-color: var(--gray-light);
    font-family: 'Inter', sans-serif;
    color: var(--black);
}

.page-title {
    font-weight: 700;
    font-size: 2.1rem;
    color: var(--black);
    letter-spacing: -1px;
}

.page-header-container {
    padding-bottom: 1.5rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid #e0e0e0;
}

.card-modern {
    border: 1px solid #e0e0e0;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
    background: var(--bg-soft);
}

.btn-primary-action {
    background-color: var(--black);
    color: #fff;
    border: 1px solid var(--black);
    border-radius: 12px;
    font-weight: 600;
    padding: 0.75rem 1.6rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary-action:hover {
    background-color: var(--gray-dark);
    color: #fff;
    border-color: var(--gray-dark);
    transform: translateY(-1px);
}

.alert-minimal {
    background: var(--gray-light);
    border: 1px solid #ddd;
    color: var(--black);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    font-weight: 500;
}

/* ---------------------------------------------------- */
/* B. TABEL MODERN */
/* ---------------------------------------------------- */
.table-minimal-modern {
    border-collapse: separate;
    border-spacing: 0 12px; 
}

.table-minimal-modern thead th {
    background-color: transparent;
    color: var(--gray-medium);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-bottom: 2px solid var(--gray-light);
    padding: 1rem 1rem;
    font-weight: 600;
}

.table-minimal-modern tbody tr {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    border-radius: 14px;
    transition: all 0.2s ease;
}

.table-minimal-modern tbody tr:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.table-minimal-modern tbody td {
    vertical-align: middle;
    padding: 1.2rem 1rem;
    color: var(--black);
    border-top: none;
    border-bottom: none;
}

.table-minimal-modern tbody tr td:first-child {
    border-top-left-radius: 14px;
    border-bottom-left-radius: 14px;
}
.table-minimal-modern tbody tr td:last-child {
    border-top-right-radius: 14px;
    border-bottom-right-radius: 14px;
}

.badge-mono {
    padding: 0.4em 0.8em;
    font-weight: 600;
    font-size: 0.7rem;
    border-radius: 8px;
    text-transform: capitalize; 
    line-height: 1.5;
}

/* Badge khusus untuk Pengeluaran */
.badge-expense {
    background: var(--expense-light);
    color: var(--expense-red);
    border: 1px solid var(--expense-red);
}

.action-group {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    align-items: center;
}

.btn-action-icon {
    font-size: 1.1rem;
    transition: color 0.2s;
    background: none;
    border: none;
    color: var(--gray-medium);
    padding: 0.3rem;
}

.btn-action-icon:hover {
    color: var(--black);
    transform: scale(1.1);
}

.btn-action-icon.edit:hover {
    color: #ffc107; /* Kuning untuk edit */
}

.btn-action-icon.delete:hover {
    color: var(--expense-red); 
}

</style>
@endsection


@section('content')
<div class="container-fluid px-4 pt-4 pb-5">
    
    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center page-header-container">
        <div>
            <h2 class="page-title mb-1 d-flex align-items-center">
                <i class="bi bi-wallet-fill me-3 fs-3 text-muted" style="line-height: 1;"></i> 
                Manajemen Pengeluaran
            </h2>
            <p class="text-muted mb-0 fs-6 ms-5 ps-1">Catat semua biaya operasional untuk menghitung keuntungan bersih.</p>
        </div>
        {{-- Total Pengeluaran Bulan Ini (Optional, tapi bagus untuk report summary) --}}
        @php
            // Asumsi $totalPengeluaran diambil dari Controller, misalnya total bulan ini
            $totalPengeluaranBulanIni = $pengeluarans->sum('jumlah'); // Jika $pengeluarans adalah semua data
        @endphp
        <div class="text-end">
            <small class="text-muted d-block">TOTAL PENGELUARAN (SAAT INI)</small>
            <h3 class="text-expense-red mb-0" style="color: var(--expense-red);">
                Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    {{-- Alert Minimalis --}}
    @if(session('success'))
        <div class="alert alert-minimal alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        
        {{-- Kolom Kiri: Form Input Pengeluaran (CREATE/EDIT) --}}
        <div class="col-lg-4">
            <div class="card card-modern p-4 sticky-top" style="top: 20px;">
                <div class="card-body p-0">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-plus-circle-fill me-2"></i> Tambah Pengeluaran
                    </h5>
                    
                    {{-- FORM INPUT PENGELUARAN --}}
                    {{-- Sesuaikan action untuk EDIT jika Anda menggunakan logika edit pada form yang sama --}}
                    <form id="pengeluaran-form" action="{{ route('pengeluaran.store') }}" method="POST">
                        @csrf
                        {{-- Field untuk EDIT (Disembunyikan jika tidak digunakan) --}}
                        <div id="method-put-container"></div>
                        <input type="hidden" name="pengeluaran_id" id="pengeluaran-id">

                        <div class="form-group mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi Pengeluaran</label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" 
                                placeholder="Contoh: Pembelian Bahan Baku" value="{{ old('deskripsi') }}" required>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="jumlah" class="form-label fw-semibold">Jumlah (Rp)</label>
                            <input type="number" step="1000" class="form-control" id="jumlah" name="jumlah" 
                                placeholder="Contoh: 500000" value="{{ old('jumlah') }}" required>
                        </div>
                        
                        <button type="submit" id="submit-btn" class="btn btn-primary-action w-100 shadow-sm">
                            <i class="bi bi-save"></i> Simpan Pengeluaran
                        </button>

                        <button type="button" id="cancel-edit-btn" class="btn btn-outline-secondary w-100 mt-2 d-none">
                            <i class="bi bi-x-circle"></i> Batal Edit
                        </button>
                    </form>
                    
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Tabel Data Pengeluaran (READ/LIST) --}}
        <div class="col-lg-8">
            <div class="card card-modern p-4">
                <div class="card-body p-0">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-table me-2"></i> Daftar Riwayat Pengeluaran
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-minimal-modern w-100 mb-0">
                            <thead class="text-start">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="45%">Deskripsi</th>
                                    <th width="20%" class="text-end">Jumlah</th>
                                    <th class="text-center" width="15%">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengeluarans as $pengeluaran)
                                <tr>
                                    <td class="text-center text-muted fw-light">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-mono badge-gray">{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d M Y') }}</span>
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $pengeluaran->deskripsi }}
                                    </td>
                                    <td class="text-end fw-bold" style="color: var(--expense-red);">
                                        -Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="action-group justify-content-center">
                                            {{-- Tombol EDIT --}}
                                            <button type="button" class="btn-action-icon edit btn-edit-pengeluaran" 
                                                data-id="{{ $pengeluaran->id }}" 
                                                data-tanggal="{{ $pengeluaran->tanggal }}" 
                                                data-deskripsi="{{ $pengeluaran->deskripsi }}" 
                                                data-jumlah="{{ $pengeluaran->jumlah }}"
                                                title="Edit Data">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            
                                            {{-- Tombol HAPUS --}}
                                            <button type="button" class="btn-action-icon delete btn-delete-pengeluaran" 
                                                data-id="{{ $pengeluaran->id }}" 
                                                data-deskripsi="{{ $pengeluaran->deskripsi }}"
                                                title="Hapus Pengeluaran">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="no-data">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-box-seam-fill fs-4 d-block mb-2"></i>
                                        <em>Belum ada catatan pengeluaran.</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- Hidden Form DELETE --}}
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-pengeluaran');
        const editButtons = document.querySelectorAll('.btn-edit-pengeluaran');
        const deleteForm = document.getElementById('delete-form');
        const mainForm = document.getElementById('pengeluaran-form');
        const formTitle = document.querySelector('.card-body h5');
        const submitBtn = document.getElementById('submit-btn');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        const methodPutContainer = document.getElementById('method-put-container');

        // Style SweetAlert agar sejalan dengan tema
        const swalMono = Swal.mixin({
            customClass: {
                popup: 'rounded-3',
                title: 'fw-bold',
                confirmButton: 'btn btn-primary-action px-4 py-2 mx-2',
                cancelButton: 'btn btn-outline-secondary px-4 py-2 mx-2'
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            iconColor: '#dc3545',
        });
        
        // --- LOGIKA DELETE ---
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const pengeluaranId = this.getAttribute('data-id');
                const deskripsi = this.getAttribute('data-deskripsi');

                swalMono.fire({
                    title: 'Hapus Pengeluaran?',
                    html: `<p class="text-muted">Anda yakin ingin menghapus pengeluaran <strong>"${deskripsi}"</strong>?</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Atur action form DELETE dan submit
                        deleteForm.action = `{{ url('pengeluaran') }}/${pengeluaranId}`;
                        deleteForm.submit();
                    }
                });
            });
        });

        // --- LOGIKA EDIT ---
        function resetForm() {
            mainForm.reset();
            mainForm.action = `{{ route('pengeluaran.store') }}`;
            methodPutContainer.innerHTML = ''; // Hapus @method('PUT')
            formTitle.innerHTML = `<i class="bi bi-plus-circle-fill me-2"></i> Tambah Pengeluaran`;
            submitBtn.innerHTML = `<i class="bi bi-save"></i> Simpan Pengeluaran`;
            cancelEditBtn.classList.add('d-none');
        }

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const tanggal = this.getAttribute('data-tanggal');
                const deskripsi = this.getAttribute('data-deskripsi');
                const jumlah = this.getAttribute('data-jumlah');

                // 1. Isi form dengan data yang ada
                document.getElementById('tanggal').value = tanggal;
                document.getElementById('deskripsi').value = deskripsi;
                document.getElementById('jumlah').value = jumlah;
                document.getElementById('pengeluaran-id').value = id; // Jika perlu

                // 2. Ubah URL form menjadi UPDATE dan tambahkan method PUT
                mainForm.action = `{{ url('pengeluaran') }}/${id}`;
                methodPutContainer.innerHTML = '@method("PUT")';

                // 3. Ubah Teks Tombol dan Judul
                formTitle.innerHTML = `<i class="bi bi-pencil-square me-2"></i> Edit Pengeluaran: ${deskripsi}`;
                submitBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Update Pengeluaran`;
                cancelEditBtn.classList.remove('d-none');

                // 4. Scroll ke atas (Opsional, agar user fokus pada form)
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        // --- LOGIKA BATAL EDIT ---
        cancelEditBtn.addEventListener('click', resetForm);
        
        // Memastikan reset dilakukan saat DOM dimuat jika ada error validation dari sesi sebelumnya
        @if (!empty(old('pengeluaran_id')) && empty(session('success')))
             // Jika form gagal validasi saat EDIT, pertahankan mode EDIT
             document.getElementById('pengeluaran-id').value = '{{ old('pengeluaran_id') }}';
             const idGagal = '{{ old('pengeluaran_id') }}';
             mainForm.action = `{{ url('pengeluaran') }}/${idGagal}`;
             methodPutContainer.innerHTML = '@method("PUT")';
             formTitle.innerHTML = `<i class="bi bi-pencil-square me-2"></i> Edit Pengeluaran (Gagal Validasi)`;
             submitBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Update Pengeluaran`;
             cancelEditBtn.classList.remove('d-none');
        @endif
    });
</script>
@endsection