@extends('dashboard.home')

@section('styles')
<style>
/* ---------------------------------------------------- */
/* A. GLOBAL BASE STYLE (Mempertahankan dan Menyempurnakan) */
/* ---------------------------------------------------- */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body,
.table,
.btn,
h2,
.card {
    font-family: 'Poppins', sans-serif;
}

/* Tombol Aksi Utama */
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

/* Tabel Styling */
.table {
    border-collapse: separate;
    border-spacing: 0 10px;
}

.table thead th {
    background-color: #e9ecef; /* Abu-abu lebih terang */
    color: #343a40; /* Hitam gelap */
    font-weight: 600;
    border: none;
    border-radius: 10px; /* Sedikit membulat */
    padding: 1rem 1.2rem;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.table tbody tr {
    background: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); /* Shadow lebih halus */
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
}

.table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem 1.2rem;
    border-top: none;
    border-bottom: none;
}

/* Garis kiri untuk Promo Aktif (Glow Modern) */
.promo-active {
    border-left: 5px solid #ffc107;
    box-shadow: 0 0 10px rgba(255, 193, 7, 0.3), 0 4px 10px rgba(0, 0, 0, 0.08); 
}

/* Toast Notification */
#successToast, #errorToast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 2000;
    opacity: 0;
    transition: opacity 0.4s ease, transform 0.4s ease;
    transform: translateX(100%);
}

#successToast.show, #errorToast.show {
    opacity: 1;
    transform: translateX(0);
}

td img {
    border-radius: 10px;
    object-fit: cover;
    height: 50px;
    width: 50px;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.15);
    transition: transform 0.3s ease;
}


/* ---------------------------------------------------- */
/* B. SLIDER PROMO MODERN (Keep) */
/* ---------------------------------------------------- */
.diskon-slider-container {
    position: relative;
    padding-top: 25px; 
    width: 100%;
    max-width: 200px;
    margin: auto;
}

.diskon-range-input {
    -webkit-appearance: none;
    appearance: none;
    height: 6px;
    background: #e2e2e2;
    border-radius: 3px;
    outline: none;
    opacity: 0.9;
    transition: opacity .2s;
}

/* Slider Thumb (Gesper) */
.diskon-range-input::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #007bff;
    cursor: pointer;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    transition: background 0.3s;
}

.slider-value-label {
    position: absolute;
    top: 0;
    transform: translateX(-50%);
    z-index: 10;
    font-size: 0.9rem;
    font-weight: 600;
    padding: 3px 8px;
    background-color: #343a40; 
    color: white;
    border-radius: 4px;
    min-width: 40px;
    text-align: center;
    pointer-events: none;
    transition: left 0.1s ease-out, opacity 0.3s;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.diskon-slider-container[data-value="0"] .slider-value-label {
    opacity: 0;
}

.harga-promo-info {
    font-size: 0.9rem;
    margin-top: 5px;
    font-weight: 500;
    min-height: 40px;
    text-align: center;
}
.harga-promo-info .text-success {
    font-size: 1.1rem;
    color: #28a745 !important;
}


/* ---------------------------------------------------- */
/* C. MODAL DURASI MODERN (Keep) */
/* ---------------------------------------------------- */
.btn-duration-config {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.6rem 1rem; 
    border-radius: 8px;
    background-color: #f1f5f9;
    color: #007bff;
    border: 1px solid #e2e8f0;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.2s ease;
    width: 100%;
    max-width: 170px; 
}

.btn-duration-config:hover {
    background-color: #e2f0ff;
    border-color: #007bff;
    color: #0056b3;
}

.btn-duration-config[disabled] {
    opacity: 0.5 !important;
    cursor: not-allowed;
    background-color: #f1f5f9;
    color: #6c757d;
}

.active-duration-text {
    font-size: 0.95rem;
    font-weight: 600;
    color: #28a745;
    display: block;
    margin-top: 5px;
}

.modal-content {
    border-radius: 15px; 
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2); 
    border: none;
}

.duration-input-modern {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    padding: 10px 15px;
    transition: border-color 0.2s;
}
.duration-input-modern:focus-within {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.duration-input-modern input {
    font-size: 1.6rem; 
    font-weight: 700;
    text-align: right;
    border: none;
    background: transparent;
    padding: 0;
    flex-grow: 1;
    max-width: 120px;
    color: #333;
}

.duration-input-modern span {
    font-size: 1rem;
    font-weight: 500;
    color: #6c757d;
    margin-left: 10px;
    min-width: 60px;
    text-align: left;
}
</style>
@endsection

@section('content')
<div class="container pt-1 pb-4">
    <h2 class="fw-bold text-dark mb-4">Pengaturan Promo Menu 🚀</h2>
    <p class="text-muted mb-4 fs-6">Atur diskon persentase dan durasi untuk setiap menu. Baris yang aktif memiliki garis kuning.</p>

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

        <div class="mb-4 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary rounded-pill shadow-lg px-4 btn-action" id="submitButton" style="font-size: 1rem; padding: 0.7rem 1.8rem;">
                <i class="bi bi-floppy"></i> Simpan Semua Pengaturan Promo
            </button>
        </div>

        <div class="table-responsive shadow-sm rounded-xl">
            <table class="table table-hover align-middle">
                <thead class="text-center">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Gambar</th>
                        <th class="text-start" style="width: 20%;">Nama Menu</th>
                        <th style="width: 15%;">Harga Normal</th>
                        <th style="width: 25%;">Diskon (%) & Harga Jual</th>
                        <th style="width: 25%;">Durasi Promo & Status</th> 
                    </tr>
                </thead>
                <tbody>
                    @php
                        $menus = $menus ?? collect();
                        $groupedMenus = $menus->groupBy(fn($menu) => $menu->kategori->nama_kategori ?? 'Tanpa Kategori');
                        $counter = 0;
                        
                        function getDurationText($durasiTersimpan) {
                            $parts = [];
                            if ($durasiTersimpan['days'] > 0) $parts[] = $durasiTersimpan['days'] . ' Hari';
                            if ($durasiTersimpan['hours'] > 0) $parts[] = $durasiTersimpan['hours'] . ' Jam';
                            if ($durasiTersimpan['minutes'] > 0) $parts[] = $durasiTersimpan['minutes'] . ' Menit';
                            
                            $text = implode(', ', $parts);
                            return $text ? $text : 'Tidak disetel';
                        }
                    @endphp

                    @foreach ($groupedMenus as $kategori => $menusByKategori)
                        {{-- STRUKTUR KATEGORI LAMA DIPULIHKAN --}}
                        <tr>
                            <td colspan="6" class="fw-semibold text-dark bg-light" style="border-top: 2px solid #000000ff;">
                                {{ $kategori }}
                            </td>
                        </tr>

                        @foreach ($menusByKategori as $menu)
                            @php
                                $counter++;
                                $hargaNormal = $menu->harga;
                                $isPromoActive = $menu->isPromoActive; 
                                
                                $hargaPromoTersimpan = $menu->harga_promo ?? 0;
                                $diskonTersimpan = 0;
                                if ($isPromoActive && $hargaPromoTersimpan > 0 && $hargaPromoTersimpan < $hargaNormal) {
                                    $diskonTersimpan = round((($hargaNormal - $hargaPromoTersimpan) / $hargaNormal) * 100);
                                }
                                
                                $durasiTersimpan = [
                                    'days' => old('durasi_hari.' . $menu->id, $menu->durasi_promo_hari ?? 0),
                                    'hours' => old('durasi_jam.' . $menu->id, $menu->durasi_promo_jam ?? 0),
                                    'minutes' => old('durasi_menit.' . $menu->id, $menu->durasi_promo_menit ?? 0),
                                ];
                                
                                $durasiText = getDurationText($durasiTersimpan); 

                                $promoEndAt = null;
                                if ($isPromoActive && $menu->promo_start_at) {
                                    $promoEndAt = (clone $menu->promo_start_at)
                                        ->addDays($menu->durasi_promo_hari)
                                        ->addHours($menu->durasi_promo_jam)
                                        ->addMinutes($menu->durasi_promo_menit);
                                }
                            @endphp

                            <tr class="{{ $isPromoActive ? 'promo-active' : '' }}" data-menu-id="{{ $menu->id }}">
                                <td class="text-center">{{ $counter }}</td>
                                <td class="text-center">
                                    @if ($menu->gambar)
                                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Menu Image" />
                                    @else
                                        <i class="bi bi-image-fill text-muted" style="font-size: 2rem;"></i>
                                    @endif
                                </td>
                                <td class="text-start fw-semibold">
                                    {{ $menu->nama_menu }}
                                    <input type="hidden" name="menu_id[]" value="{{ $menu->id }}">
                                </td>
                                <td class="text-end" data-harga-normal="{{ $hargaNormal }}">
                                    Rp{{ number_format($hargaNormal, 0, ',', '.') }}
                                    <small class="text-muted d-block mt-1">Stok: <span id="stokNormal-{{ $menu->id }}">{{ $menu->stok }}</span></small>
                                </td>
                                <td>
                                    <div class="diskon-slider-container" data-value="{{ $diskonTersimpan }}">
                                        {{-- Label Persentase --}}
                                        <div class="slider-value-label" id="sliderLabel-{{ $menu->id }}">{{ $diskonTersimpan > 0 ? $diskonTersimpan : 0 }}%</div>

                                        {{-- Input Range --}}
                                        <input type="range" name="diskon_range[{{ $menu->id }}]" 
                                            class="form-range diskon-range-input"
                                            min="0" max="99" step="1" 
                                            value="{{ old('diskon_range.' . $menu->id, $diskonTersimpan) }}"
                                            data-menu-id="{{ $menu->id }}"
                                            id="diskonRange-{{ $menu->id }}">

                                        <input type="hidden" name="harga_promo[{{ $menu->id }}]" id="hargaPromoHidden-{{ $menu->id }}" value="{{ old('harga_promo.' . $menu->id, $menu->harga_promo ?? '0') }}">
                                    </div>

                                    <div id="hargaPromoInfo-{{ $menu->id }}" class="harga-promo-info">
                                        @if ($isPromoActive)
                                            <span class="d-block text-success fw-bold">Jual: Rp{{ number_format($hargaPromoTersimpan, 0, ',', '.') }}</span>
                                            <small class="text-info d-block">Hemat: Rp{{ number_format($hargaNormal - $hargaPromoTersimpan, 0, ',', '.') }}</small>
                                        @endif
                                    </div>
                                </td>
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
                                            <span class="active-duration-text">{{ $durasiText }}</span>
                                            <small class="text-danger d-block mt-1 fw-bold">Berakhir: {{ $promoEndAt->format('H:i, d M Y') }}</small>
                                        @else
                                            <small class="text-muted d-block mt-1">Nonaktif</small>
                                        @endif
                                    </div>

                                    <input type="hidden" name="durasi_hari[{{ $menu->id }}]" id="durasiHariHidden-{{ $menu->id }}" value="{{ $durasiTersimpan['days'] }}">
                                    <input type="hidden" name="durasi_jam[{{ $menu->id }}]" id="durasiJamHidden-{{ $menu->id }}" value="{{ $durasiTersimpan['hours'] }}">
                                    <input type="hidden" name="durasi_menit[{{ $menu->id }}]" id="durasiMenitHidden-{{ $menu->id }}" value="{{ $durasiTersimpan['minutes'] }}">
                                </td>
                            </tr>
                            {{-- SPACER ROW LAMA DIPULIHKAN --}}
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

{{-- MODAL DURASI (Keep) --}}
<div class="modal fade" id="durationModal" tabindex="-1" aria-labelledby="durationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="durationModalLabel">⏱️ Atur Durasi Promo: <span id="modalMenuName" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalMenuId">

                <p class="text-muted text-center mb-4">Waktu akan dihitung sejak tombol "Simpan Semua Pengaturan Promo" ditekan.</p>

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
                <button type="button" class="btn btn-primary rounded-pill btn-action" id="saveDurationButton">
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
    
    // Fungsi formatDurationText DIBIARKAN SAMA (sesuai kebutuhan JS)
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

    // ----------------------------------------------------
    // START: TOAST & SLIDER LOGIC (Keep)
    // ----------------------------------------------------
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

        // Logic untuk menonaktifkan tombol durasi jika diskon 0%
        if (diskonPersen === 0) {
            durationButton.setAttribute('disabled', 'true'); 
            
            // Atur durasi tersembunyi menjadi 0
            document.getElementById(`durasiHariHidden-${menuId}`).value = 0;
            document.getElementById(`durasiJamHidden-${menuId}`).value = 0;
            document.getElementById(`durasiMenitHidden-${menuId}`).value = 0;
            
            // Update display durasi menjadi Nonaktif
            const durationDisplay = document.getElementById(`durationDisplay-${menuId}`);
            durationDisplay.innerHTML = formatDurationText(0, 0, 0);

        } else {
            durationButton.removeAttribute('disabled');
        }

        // Logic untuk menandai baris 'promo-active'
        if (tr) {
             // Promo aktif jika ada diskon (>0) DAN ada durasi yang disetel (>0)
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
            
        } else {
            hargaPromoHidden.value = '0'; 
            hargaPromoInfo.innerHTML = '';
        }
        
        sliderLabel.textContent = diskonPersen + '%';
        
        // Menyesuaikan posisi label agar mengikuti thumb slider
        const max = parseInt(input.getAttribute('max'));
        const min = parseInt(input.getAttribute('min'));
        const range = max - min;
        const value = parseInt(input.value);
        
        let position = ((value - min) / range) * 100;
        // Penyesuaian offset: membuat label sedikit lebih ke tengah thumb
        let offset = 8 - position * 0.15; // Logika ini membantu label tetap di tengah
        sliderLabel.style.left = `calc(${position}% + (${offset}px))`; 
        
        checkPromoActiveStatus(menuId); 
    }

    diskonRangeInputs.forEach(input => {
        calculateAndDisplayPromo(input); 
        input.addEventListener('input', function() {
            calculateAndDisplayPromo(this);
        });
    });
    
    // ----------------------------------------------------
    // END: SLIDER LOGIC
    // START: DURATION MODAL LOGIC (Keep)
    // ----------------------------------------------------

    const durationModal = document.getElementById('durationModal');
    
    document.querySelectorAll('.btn-duration-config').forEach(button => {
        button.addEventListener('click', function(event) {
            if (this.hasAttribute('disabled')) {
                event.stopPropagation();
                event.preventDefault(); 
                const menuName = this.getAttribute('data-menu-name');
                alert(`⚠️ Harap atur persentase diskon untuk ${menuName} (minimal 1%) terlebih dahulu sebelum mengatur durasi!`);
            }
        });
    });

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

    document.getElementById('saveDurationButton').addEventListener('click', function() {
        const modal = bootstrap.Modal.getInstance(durationModal);
        
        const menuId = document.getElementById('modalMenuId').value;
        const hari = parseInt(document.getElementById('modalDurasiHari').value, 10) || 0;
        const jam = parseInt(document.getElementById('modalDurasiJam').value, 10) || 0;
        const menit = parseInt(document.getElementById('modalDurasiMenit').value, 10) || 0;
        
        // Validasi
        if (hari < 0 || jam < 0 || menit < 0 || jam > 23 || menit > 59) {
            alert('Input durasi tidak valid!');
            return;
        }

        // Update hidden fields
        document.getElementById(`durasiHariHidden-${menuId}`).value = hari;
        document.getElementById(`durasiJamHidden-${menuId}`).value = jam;
        document.getElementById(`durasiMenitHidden-${menuId}`).value = menit;
        
        // Update tampilan di tabel
        const durationDisplay = document.getElementById(`durationDisplay-${menuId}`);
        durationDisplay.innerHTML = formatDurationText(hari, jam, menit);
        
        // Check Status Promo Active
        checkPromoActiveStatus(menuId);

        modal.hide();
    });
});
</script>
@endsection