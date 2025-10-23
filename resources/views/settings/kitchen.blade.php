@extends('dashboard.home')

@section('content')
    <div class="container-fluid">
        
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-cog fa-fw me-2"></i>
                    Pengaturan Kitchen
                </h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Terjadi kesalahan saat menyimpan data. Silakan periksa input Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fab fa-telegram-plane me-2"></i>
                    Atur Chat ID Penerima Pesanan Dapur
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Chat ID Telegram ini akan menerima rincian pesanan secara otomatis (via Bot Telegram) setiap kali kasir menyelesaikan transaksi.
                </p>
                
                <form action="{{ route('settings.kitchen.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="telegram_kitchen_chat_id" class="form-label fw-bold">Chat ID Telegram Kitchen</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fab fa-telegram-plane fa-fw"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control @error('telegram_kitchen_chat_id') is-invalid @enderror" 
                                id="telegram_kitchen_chat_id" 
                                name="telegram_kitchen_chat_id" 
                                value="{{ old('telegram_kitchen_chat_id', $setting->value) }}" 
                                placeholder="-1001234567890 atau 12345678"
                                aria-describedby="chatIdHelp">
                            
                            @error('telegram_kitchen_chat_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div id="chatIdHelp" class="form-text mt-2 text-info">
                            <i class="fas fa-info-circle me-1"></i>
                            Masukkan <strong>Chat ID Grup</strong> (biasanya dimulai dengan <code>(-)</code>) atau <strong>Chat ID Pengguna</strong> (angka). Anda bisa menggunakan <a href="https://t.me/getidsbot" target="_blank" rel="noopener noreferrer">@getidsbot</a> untuk mendapatkan ID-nya.
                        </div>
                    </div>
                    
                    <hr class="mt-4">
                    
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i> 
                        </span>
                        <span class="text">
                            @if($setting->value)
                                <strong>UBAH</strong> Chat ID
                            @else
                                <strong>TAMBAHKAN</strong> Chat ID
                            @endif
                        </span>
                    </button>

                </form>
            </div>
        </div>
    </div>
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @endpush
@endsection