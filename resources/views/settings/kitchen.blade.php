@extends('dashboard.home')

@section('content')
    <div class="container-fluid">
        
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-cog fa-fw me-2"></i> Pengaturan WhatsApp Kitchen
                </h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> Terjadi kesalahan saat menyimpan data. Silakan periksa input Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fab fa-whatsapp me-2"></i> Atur Nomor Penerima Pesanan Dapur
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Nomor WhatsApp ini akan menerima rincian pesanan secara otomatis setiap kali kasir menyelesaikan transaksi.</p>
                
                <form action="{{ route('settings.kitchen.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="wa_kitchen_number" class="form-label fw-bold">Nomor WhatsApp Kitchen</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">+62</span>
                            <input 
                                type="text" 
                                class="form-control @error('wa_kitchen_number') is-invalid @enderror" 
                                id="wa_kitchen_number" 
                                name="wa_kitchen_number" 
                                value="{{ old('wa_kitchen_number', $setting->value) }}" 
                                placeholder="81234567890">
                            
                            @error('wa_kitchen_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text mt-2 text-info">
                            Masukkan nomor tanpa angka **'0'** di awal, dan tanpa spasi/strip. Contoh: **81234567890** (sistem akan otomatis menambahkan +62).
                        </div>
                    </div>
                    
                    <hr>
                    
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text">Simpan Pengaturan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk memastikan Font Awesome dimuat (jika belum di layout utama) --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @endpush
@endsection