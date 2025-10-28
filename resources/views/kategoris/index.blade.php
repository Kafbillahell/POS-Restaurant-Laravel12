@extends('dashboard.home')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body,
        .table,
        .btn,
        h2,
        .card {
            font-family: 'Poppins', sans-serif;
        }

        .kategori-heading {
            font-size: 1.7rem;
            font-weight: 700;
            color: #2a3950;
            letter-spacing: 0.01em;
        }

        /* --- Table Header --- */
        .table thead th {
            background: linear-gradient(90deg, #f4f7fb 78%, #e7eefa 100%);
            color: #284866;
            font-weight: 600;
            border: none;
            padding: 1rem 1.2rem;
            border-radius: 10px 10px 0 0;
            font-size: 1rem;
            letter-spacing: .01em;
        }

        /* --- Table Body --- */
        .table tbody tr {
            background: #fff;
            box-shadow: 0 2px 6px rgb(42 57 80 / 8%);
            border-radius: 9px;
            transition: transform 0.17s, box-shadow 0.17s;
        }

        .table tbody tr:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 6px 18px rgb(42 57 80 / 13%);
            background: #f5fafd;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem 1.2rem;
            font-size: 1.01rem;
            color: #284866;
        }

        .btn {
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
            border-radius: 50px !important;
            font-size: 1rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-action i {
            font-size: 1.1rem;
        }


        .card {
            border: none;
            border-radius: 18px;
            background: #f9fbfd;
            box-shadow: 0 6px 18px #28486613, 0 2px 8px #d7e0ec0a;
        }

        #successToast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            opacity: 0;
            transition: opacity 0.4s ease;
            font-size: 1.05rem;
            border-radius: 1rem;
            font-weight: 500;
            background: #e7f7ed;
            color: #2e6051;
            border: 1px solid #b9dbcc;
            box-shadow: 0 2px 10px #b9dbcc55;
        }

        #successToast.show {
            opacity: 1;
        }

        @media (max-width: 768px) {
            .kategori-heading {
                font-size: 1.13rem;
            }

            .btn {
                font-size: .97rem;
            }

            .table th,
            .table td {
                padding: .75rem .65rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="fw-semibold text-primary kategori-heading mb-0">📂 Daftar Kategori</h2>
            <a href="{{ route('kategoris.create') }}" class="btn btn-success shadow-sm px-4 btn-action">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
            </a>
        </div>

        @if(session('success'))
            <div id="successToast" class="alert alert-success shadow-sm rounded">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0 rounded">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle text-center m-0">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th class="text-start">Nama Kategori</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $kategori)
                                <tr>
                                    <td>{{ $kategori->id }}</td>
                                    <td class="text-start">{{ $kategori->nama_kategori }}</td>
                                    <td>
                                        <a href="{{ route('kategoris.edit', $kategori->id) }}"
                                            class="btn btn-warning btn-sm px-3 btn-action">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form action="{{ route('kategoris.destroy', $kategori->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm px-3 btn-action"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toast = document.getElementById('successToast');
            if (toast) {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3500);
            }
        });
    </script>
@endsection