@extends('dashboard.home')

@section('styles')
<style>
/* Mempertahankan semua style awal */
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

body,
.table,
.btn,
h2,
.card {
    font-family: 'Poppins', sans-serif;
}

.btn {
    transition: background-color 0.3s ease, color 0.3s ease;
    font-weight: 600;
}

.table {
    border-collapse: separate;
    border-spacing: 0 10px;
}

.table thead th {
    background-color: #f1f5f9;
    color: #000000ff;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    padding: 1rem 1.2rem;
}

.table tbody tr {
    background: #fff;
    box-shadow: 0 3px 8px rgb(0 0 0 / 0.1);
    border-radius: 12px;
    transition: transform 0.2s ease;
}

.table tbody tr:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgb(0 0 0 / 0.15);
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem 1.2rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

td img {
    border-radius: 10px;
    object-fit: cover;
    height: 50px;
    width: 50px;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
    transition: transform 0.3s ease;
}

.input-promo {
    text-align: right;
    max-width: 120px;
}

.promo-active {
    border-left: 5px solid #ffc107;
}

.stok-error {
    color: #dc3545;
    font-size: 0.8rem;
    margin-top: 5px;
    font-weight: 500;
}

.harga-promo-info {
    font-size: 0.9rem;
    margin-top: 5px;
    font-weight: 500;
    min-height: 40px;
}

#successToast, #errorToast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 2000;
    opacity: 0;
    transition: opacity 0.4s ease;
}

#successToast.show, #errorToast.show {
    opacity: 1;
}

.diskon-slider-container {
    position: relative;
    padding-top: 20px;
    width: 100%;
    max-width: 200px;
    margin: auto;
}

.diskon-range-input {
    width: 100%;
}

.slider-value-label {
    position: absolute;
    top: 0;
    transform: translateX(-50%);
    z-index: 10;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 2px 6px;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    min-width: 40px;
    text-align: center;
    pointer-events: none;
    transition: left 0.05s ease-out, opacity 0.3s;
}

.diskon-slider-container[data-value="0"] .slider-value-label {
    opacity: 0;
}

/* START: New Modern Duration UI Styles */
.btn-duration-config {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    background-color: #f1f5f9;
    color: #007bff;
    border: 1px solid #e2e8f0;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.2s ease;
    width: 100%;
    max-width: 150px;
}

.btn-duration-config:hover {
    background-color: #e2f0ff;
    border-color: #007bff;
    color: #0056b3;
}

.active-duration-text {
    font-size: 0.95rem;
    font-weight: 600;
    color: #28a745;
    display: block;
    margin-top: 5px;
}

.modal-content {
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    border: none;
}

.modal-header {
    border-bottom: none;
    padding: 1.5rem 2rem 0.5rem;
}

.modal-body {
    padding: 1rem 2rem 2rem;
}

.duration-input-modern {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    background-color: #f8f9fa;
    border-radius: 12px;
    padding: 10px 15px;
}

.duration-input-modern input {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: right;
    border: none;
    background: transparent;
    padding: 0;
    flex-grow: 1;
    max-width: 100px;
    color: #333;
}

.duration-input-modern span {
    font-size: 1.2rem;
    font-weight: 500;
    color: #6c757d;
    margin-left: 10px;
    min-width: 60px;
}

.duration-input-modern input:focus {
    box-shadow: none;
}
/* END: New Modern Duration UI Styles */
</style>
@endsection

@section('content')
<div class="container pt-1 pb-4">
    <h2 class="fw-semibold text-dark mb-4">Promo</h2>
    <p class="text-muted mb-4 fs-6"></p>

    @if (session('error'))
        <div id="errorToast" class="alert alert-danger shadow-sm rounded">
            <i class="bi bi-x-octagon me-2"></i>{!! session('error') !!}
        </div>
    @endif

    @if (session('success'))
        <div id="successToast" class="alert alert-success shadow-sm rounded">
            <i class="bi bi-check-circle me-2"></i>{!! session('success') !!}
        </div>
    @endif

    <form id="promoForm" action="{{ route('promo.update') }}" method="POST">
        @csrf

        <div class="mb-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary rounded-pill shadow-sm px-4 btn-action" id="submitButton">
                <i class="bi bi-floppy"></i> Simpan Semua Pengaturan Promo
            </button>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Gambar</th>
                        <th class="text-start" style="width: 20%;">Nama Menu</th>
                        <th style="width: 15%;">Harga Normal</th>
                        <th style="width: 25%;">Diskon (%) & Harga Jual</th>
                        <th style="width: 20%;">Durasi Promo</th> {{-- Kolom ini diubah namanya --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                        $menus = $menus ?? collect();
                        $groupedMenus = $menus->groupBy(fn($menu) => $menu->kategori->nama_kategori ?? 'Tanpa Kategori');
                        $counter = 0;

                        // Mock data untuk Durasi
                        

                        function getDurationText($durasiTersimpan) {
                            $parts = [];
                            if ($durasiTersimpan['days'] > 0) $parts[] = $durasiTersimpan['days'] . ' Hari';
                            if ($durasiTersimpan['hours'] > 0) $parts[] = $durasiTersimpan['hours'] . ' Jam';
                            if ($durasiTersimpan['minutes'] > 0) $parts[] = $durasiTersimpan['minutes'] . ' Menit';
                            return implode(', ', $parts);
                        }
                    @endphp

                    @foreach ($groupedMenus as $kategori => $menusByKategori)
                        <tr>
                            <td colspan="6" class="fw-semibold text-dark bg-light" style="border-top: 2px solid #000000ff;">
                                {{ $kategori }}
                            </td>
                        </tr>

                        @foreach ($menusByKategori as $menu)
                           @php
    $counter++;
    $hargaNormal = $menu->harga;
    
    // 🔥 PENTING: Gunakan ACCESSOR isPromoActive dari Model
    // Accessor ini sudah menangani logika kedaluwarsa waktu.
    $isPromoActive = $menu->isPromoActive; 
    
    // Ambil nilai promo yang akan ditampilkan jika aktif
    $hargaPromoTersimpan = $menu->harga_promo ?? 0;
    $diskonTersimpan = 0;
    if ($isPromoActive && $hargaPromoTersimpan > 0 && $hargaPromoTersimpan < $hargaNormal) {
        $diskonTersimpan = round((($hargaNormal - $hargaPromoTersimpan) / $hargaNormal) * 100);
    }
    
    // 🔥 Durasi yang TERSIMPAN (untuk diisi kembali ke Modal/Form)
    $durasiTersimpan = [
        'days' => old('durasi_hari.' . $menu->id, $menu->durasi_promo_hari ?? 0),
        'hours' => old('durasi_jam.' . $menu->id, $menu->durasi_promo_jam ?? 0),
        'minutes' => old('durasi_menit.' . $menu->id, $menu->durasi_promo_menit ?? 0),
    ];
    
    // Ambil teks durasi
    $durasiText = getDurationText($durasiTersimpan); // Fungsi PHP Anda

    // 🔥 Tentukan waktu berakhir (untuk ditampilkan)
    $promoEndAt = null;
    if ($isPromoActive && $menu->promo_start_at) {
        $promoEndAt = (clone $menu->promo_start_at)
            ->addDays($menu->durasi_promo_hari)
            ->addHours($menu->durasi_promo_jam)
            ->addMinutes($menu->durasi_promo_menit);
    }
@endphp

{{-- Baris HTML <tr> akan tetap menggunakan $isPromoActive: --}}
<tr class="{{ $isPromoActive ? 'promo-active' : '' }}" data-menu-id="{{ $menu->id }}">
                                <td class="text-center">{{ $counter }}</td>
                                <td class="text-center">
                                    @if ($menu->gambar)
                                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Menu Image" />
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-start">
                                    {{ $menu->nama_menu }}
                                    <input type="hidden" name="menu_id[]" value="{{ $menu->id }}">
                                </td>
                                <td class="text-end" data-harga-normal="{{ $hargaNormal }}">
                                    Rp{{ number_format($hargaNormal, 0, ',', '.') }}
                                    <small class="text-muted d-block">Stok Normal: <span id="stokNormal-{{ $menu->id }}">{{ $menu->stok }}</span></small>
                                </td>
                                <td>
                                    <div class="diskon-slider-container" data-value="{{ $diskonTersimpan }}">
                                        <div class="slider-value-label" id="sliderLabel-{{ $menu->id }}">{{ $diskonTersimpan > 0 ? $diskonTersimpan : 0 }}%</div>

                                        <input type="range" name="diskon_range[{{ $menu->id }}]" 
                                                class="form-range diskon-range-input"
                                                min="0" max="99" step="1" 
                                                value="{{ old('diskon_range.' . $menu->id, $diskonTersimpan) }}"
                                                title="Geser untuk menentukan persentase diskon."
                                                data-menu-id="{{ $menu->id }}"
                                                id="diskonRange-{{ $menu->id }}">

                                        <input type="hidden" name="harga_promo[{{ $menu->id }}]" id="hargaPromoHidden-{{ $menu->id }}" value="{{ old('harga_promo.' . $menu->id, $menu->harga_promo ?? '0') }}">
                                    </div>

                                    <div id="hargaPromoInfo-{{ $menu->id }}" class="harga-promo-info mt-2">
                                        @if ($isPromoActive)
                                            <span class="d-block text-success fw-bold">Jual: Rp{{ number_format($hargaPromoTersimpan, 0, ',', '.') }}</span>
                                            <small class="text-info d-block">Hemat: Rp{{ number_format($hargaNormal - $hargaPromoTersimpan, 0, ',', '.') }}</small>
                                        @endif
                                    </div>
                                </td>
                                {{-- KOLOM BARU UNTUK DURASI PROMO (Modern UI) --}}
                                <td class="text-center">
    <button type="button" 
            class="btn-duration-config"
            data-bs-toggle="modal" 
            data-bs-target="#durationModal"
            data-menu-id="{{ $menu->id }}"
            data-menu-name="{{ $menu->nama_menu }}"
            data-durasi-hari="{{ $durasiTersimpan['days'] }}"
            data-durasi-jam="{{ $durasiTersimpan['hours'] }}"
            data-durasi-menit="{{ $durasiTersimpan['minutes'] }}">
        <i class="bi bi-clock-history me-2"></i> Atur Waktu
    </button>
    
    <div class="duration-display" id="durationDisplay-{{ $menu->id }}">
        @if ($isPromoActive)
            {{-- Tampilkan durasi dan waktu berakhir --}}
            <span class="active-duration-text">{{ $durasiText }}</span>
            <small class="text-danger d-block mt-1">Berakhir: {{ $promoEndAt->format('H:i:s, d M') }}</small>
        @else
            <small class="text-muted d-block mt-1">Nonaktif</small>
        @endif
    </div>

    {{-- Input tersembunyi untuk Durasi, akan diisi oleh Modal --}}
    <input type="hidden" name="durasi_hari[{{ $menu->id }}]" id="durasiHariHidden-{{ $menu->id }}" value="{{ $durasiTersimpan['days'] }}">
    <input type="hidden" name="durasi_jam[{{ $menu->id }}]" id="durasiJamHidden-{{ $menu->id }}" value="{{ $durasiTersimpan['hours'] }}">
    <input type="hidden" name="durasi_menit[{{ $menu->id }}]" id="durasiMenitHidden-{{ $menu->id }}" value="{{ $durasiTersimpan['minutes'] }}">
</td>
                                {{-- END KOLOM BARU --}}
                            </tr>

                            <tr>
                                <td colspan="6" style="padding-top: 1rem;"></td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>

<div class="modal fade" id="durationModal" tabindex="-1" aria-labelledby="durationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="durationModalLabel">Atur Durasi Promo: <span id="modalMenuName" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalMenuId">

                <p class="text-muted text-center mb-4">Atur waktu promo (0 untuk menonaktifkan)</p>

                <div class="duration-input-modern">
                    <input type="number" id="modalDurasiHari" class="form-control" min="0" max="365" placeholder="0">
                    <span>Hari</span>
                </div>

                <div class="duration-input-modern">
                    <input type="number" id="modalDurasiJam" class="form-control" min="0" max="23" placeholder="0">
                    <span>Jam</span>
                </div>

                <div class="duration-input-modern">
                    <input type="number" id="modalDurasiMenit" class="form-control" min="0" max="59" placeholder="0">
                    <span>Menit</span>
                </div>

            </div>
            <div class="modal-footer" style="border-top: none; padding: 0 2rem 2rem;">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill" id="saveDurationButton">
                    <i class="bi bi-save me-1"></i> Simpan Durasi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    function formatRupiah(angka) {
        if (isNaN(angka) || angka === null) return '0';
        return new Intl.NumberFormat('id-ID').format(Math.round(angka));
    }
    
    function formatDurationText(d, h, m) {
        d = parseInt(d) || 0;
        h = parseInt(h) || 0;
        m = parseInt(m) || 0;
        
        const parts = [];
        if (d > 0) parts.push(d + ' Hari');
        if (h > 0) parts.push(h + ' Jam');
        if (m > 0) parts.push(m + ' Menit');
        
        if (parts.length === 0) {
            return '<small class="text-muted d-block mt-1">Nonaktif</small>';
        }
        return '<span class="active-duration-text">' + parts.join(', ') + '</span>';
    }

    const successToast = document.getElementById('successToast');
    if (successToast) {
        successToast.classList.add('show');
        setTimeout(() => {
            successToast.classList.remove('show');
        }, 4000);
    }
    
    const errorToast = document.getElementById('errorToast');
    if (errorToast) {
        errorToast.classList.add('show');
        setTimeout(() => {
            errorToast.classList.remove('show');
        }, 5000);
    }

    const diskonRangeInputs = document.querySelectorAll('.diskon-range-input');

    function checkPromoActiveStatus(menuId) {
        const tr = document.querySelector(`tr[data-menu-id="${menuId}"]`);
        const diskonRange = document.getElementById(`diskonRange-${menuId}`);
        const diskonPersen = parseInt(diskonRange.value) || 0;
        
        const durasiHari = parseInt(document.getElementById(`durasiHariHidden-${menuId}`).value);
        const durasiJam = parseInt(document.getElementById(`durasiJamHidden-${menuId}`).value);
        const durasiMenit = parseInt(document.getElementById(`durasiMenitHidden-${menuId}`).value);
        
        const durationButton = tr.querySelector('.btn-duration-config');

        if (diskonPersen === 0) {
            durationButton.style.opacity = 0.4;
            durationButton.style.pointerEvents = 'auto';
            durationButton.setAttribute('disabled', true); 

            document.getElementById(`durasiHariHidden-${menuId}`).value = 0;
            document.getElementById(`durasiJamHidden-${menuId}`).value = 0;
            document.getElementById(`durasiMenitHidden-${menuId}`).value = 0;
            
            const durationDisplay = document.getElementById(`durationDisplay-${menuId}`);
            durationDisplay.innerHTML = formatDurationText(0, 0, 0);

        } else {
            durationButton.style.opacity = 1;
            durationButton.style.pointerEvents = 'auto';
            durationButton.removeAttribute('disabled');
        }

        if (tr) {
             if (diskonPersen > 0 && (durasiHari > 0 || durasiJam > 0 || durasiMenit > 0)) {
                 tr.classList.add('promo-active');
             } else {
                 tr.classList.remove('promo-active');
             }
        }
    }

    function calculateAndDisplayPromo(input) {
        const menuId = input.getAttribute('data-menu-id');
        const diskonPersen = parseInt(input.value) || 0;
        
        const tr = input.closest('tr');
        const hargaNormalTd = tr.querySelector('td[data-harga-normal]');
        const hargaNormal = parseInt(hargaNormalTd.getAttribute('data-harga-normal'));

        const hargaPromoHidden = document.getElementById(`hargaPromoHidden-${menuId}`);
        const hargaPromoInfo = document.getElementById(`hargaPromoInfo-${menuId}`);
        const sliderLabel = document.getElementById(`sliderLabel-${menuId}`);
        const container = input.closest('.diskon-slider-container');
        
        container.setAttribute('data-value', diskonPersen);

        if (diskonPersen > 0) {
            const diskonNominal = (hargaNormal * diskonPersen) / 100;
            const hargaJualPromo = Math.floor(hargaNormal - diskonNominal); 
            const hemat = hargaNormal - hargaJualPromo;

            hargaPromoHidden.value = hargaJualPromo;

            hargaPromoInfo.innerHTML = `
                <span class="d-block text-success fw-bold">Jual: Rp${formatRupiah(hargaJualPromo)}</span>
                <small class="text-info d-block">Hemat: Rp${formatRupiah(hemat)}</small>
            `;
            
            checkPromoActiveStatus(menuId); 
        } else {
            hargaPromoHidden.value = '0'; 
            hargaPromoInfo.innerHTML = '';
            
            const durationDisplay = document.getElementById(`durationDisplay-${menuId}`);
            durationDisplay.innerHTML = formatDurationText(0, 0, 0);

            checkPromoActiveStatus(menuId); 
        }
        
        sliderLabel.textContent = diskonPersen + '%';
        
        const max = parseInt(input.getAttribute('max'));
        const min = parseInt(input.getAttribute('min'));
        const range = max - min;
        const value = parseInt(input.value);
        
        let position = ((value - min) / range) * 100;
        sliderLabel.style.left = `calc(${position}% + (${8 - position * 0.15}px))`; 
    }

    diskonRangeInputs.forEach(input => {
        calculateAndDisplayPromo(input); 
        input.addEventListener('input', function() {
            calculateAndDisplayPromo(this);
        });
    });

    const durationModal = document.getElementById('durationModal');
    
    document.querySelectorAll('.btn-duration-config').forEach(button => {
        button.removeEventListener('click', handleDurationButtonClick);
        button.addEventListener('click', handleDurationButtonClick);
    });
    
    function handleDurationButtonClick(event) {
        if (this.hasAttribute('disabled')) {
            event.stopPropagation();
            event.preventDefault(); 
            alert('⚠️ Harap atur persentase diskon (slider) terlebih dahulu sebelum mengatur durasi!');
        }
    }

    durationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; 

        if (button.hasAttribute('disabled')) {
             event.preventDefault(); 
             return;
        }

        const menuId = button.getAttribute('data-menu-id');
        const menuName = button.getAttribute('data-menu-name');
        
        const h_input = document.getElementById(`durasiHariHidden-${menuId}`);
        const j_input = document.getElementById(`durasiJamHidden-${menuId}`);
        const m_input = document.getElementById(`durasiMenitHidden-${menuId}`);

        document.getElementById('modalMenuId').value = menuId;
        document.getElementById('modalMenuName').textContent = menuName;
        document.getElementById('modalDurasiHari').value = parseInt(h_input.value) || 0;
        document.getElementById('modalDurasiJam').value = parseInt(j_input.value) || 0;
        document.getElementById('modalDurasiMenit').value = parseInt(m_input.value) || 0;
    });

    durationModal.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
    });
    
    document.getElementById('saveDurationButton').addEventListener('click', function() {
        const modal = bootstrap.Modal.getInstance(durationModal);
        
        const menuId = document.getElementById('modalMenuId').value;
        const hari = parseInt(document.getElementById('modalDurasiHari').value, 10) || 0;
        const jam = parseInt(document.getElementById('modalDurasiJam').value, 10) || 0;
        const menit = parseInt(document.getElementById('modalDurasiMenit').value, 10) || 0;

        document.getElementById(`durasiHariHidden-${menuId}`).value = hari;
        document.getElementById(`durasiJamHidden-${menuId}`).value = jam;
        document.getElementById(`durasiMenitHidden-${menuId}`).value = menit;
        
        const durationDisplay = document.getElementById(`durationDisplay-${menuId}`);
        durationDisplay.innerHTML = formatDurationText(hari, jam, menit);
        
        checkPromoActiveStatus(menuId);

        modal.hide();
    });
});
</script>
@endsection