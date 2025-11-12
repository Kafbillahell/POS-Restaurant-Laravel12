@extends('dashboard.home')

@section('title', 'Detail Laporan Penjualan Bulanan')

@section('content')

    
    <style>
        :root {
            --primary-color: #3f51b5;
            
            --primary-color-light: #5c6bc0;
            --primary-color-dark: #303f9f;
            --primary-color-rgb: 63, 81, 181;

            --accent-color: #00bcd4;
            
            --accent-color-light: #4dd0e1;

            --text-color-dark: #212529;
            --text-color-medium: #495057;
            --text-color-light: #adb5bd;

            --bg-page: #f4f6f9;
            --bg-card-light: #ffffff;
            --bg-subtle: #e9ecef;

            --border-color-main: #dee2e6;
            --border-color-light: #f1f3f5;

            --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-medium: 0 8px 25px rgba(0, 0, 0, 0.1);

            --radius-default: 0.75rem;
            --radius-sharp: 0.5rem;

            --success-color: #28a745;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }

       
        body {
            background-color: var(--bg-page);
            font-family: 'Inter', 'Poppins', sans-serif;
            color: var(--text-color-dark);
            line-height: 1.5;
        }

        .container {
            padding-top: 2rem;
            padding-bottom: 3rem;
            max-width: 1300px;
            margin: auto;
        }

        
        .page-header-container {
            margin-bottom: 2rem;
            padding: 2.5rem 2rem;
            background: linear-gradient(135deg, rgba(63, 81, 181, 0.95) 0%, rgba(92, 107, 192, 0.9) 100%);
            border-radius: var(--radius-default);
            color: white;
            box-shadow: var(--shadow-medium);
            position: relative;
            z-index: 10;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .header-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: center;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            margin-bottom: 0.5rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .page-meta-info {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.75);
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .page-meta-info strong {
            font-weight: 700;
            color: white;
        }

        .page-meta-item i {
            color: var(--accent-color-light);
            font-size: 1em;
            margin-right: 0.3rem;
        }

        
        .global-summary-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .summary-card {
            padding: 1.5rem 2rem;
            background-color: var(--bg-card-light);
            border-radius: var(--radius-default);
            box-shadow: var(--shadow-soft);
            border-left: 5px solid var(--accent-color);
            transition: transform 0.2s ease;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }

        .summary-card.total-sales {
            border-left-color: var(--primary-color);
        }

        .summary-card .label {
            font-size: 0.9rem;
            color: var(--text-color-medium);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
        }

        .summary-card .value {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--text-color-dark);
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .summary-card.total-sales .value {
            color: var(--primary-color);
        }

        
        .orders-list-header {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-color-dark);
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color-light);
            padding-bottom: 0.5rem;
        }

        .order-accordion {
            margin-bottom: 1rem;
            border-radius: var(--radius-default);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color-main);
        }

        .accordion-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.75rem;
            background-color: var(--bg-card-light);
            border: none;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.2s ease, border-bottom-color 0.2s ease;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-color-dark);
        }

        .accordion-toggle:hover {
            background-color: var(--bg-subtle);
        }

        .accordion-toggle[aria-expanded="true"] {
            border-bottom: 1px solid var(--border-color-main);
            background-color: var(--bg-subtle);
        }

        .order-id-display {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .toggle-meta-item {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-color-medium);
        }

        .toggle-meta-item strong {
            color: var(--text-color-dark);
            font-weight: 700;
            font-size: 1.1em;
        }

        .toggle-icon i {
            transition: transform 0.3s ease;
            color: var(--primary-color);
        }

        .accordion-toggle[aria-expanded="true"] .toggle-icon i {
            transform: rotate(180deg);
        }

        .accordion-content {
            background-color: var(--bg-card-light);
            overflow: hidden;
            max-height: 0;
            
            transition: max-height 0.6s ease-in-out, padding 0.6s ease-in-out;
        }

        .accordion-content.expanded {
            
            padding: 1.5rem 1.75rem;
        }

        .payment-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: var(--radius-sharp);
            color: white;
            background-color: var(--info-color);
        }

        .payment-badge.cash {
            background-color: var(--success-color);
        }

        .payment-badge.non-cash {
            background-color: var(--accent-color);
        }

        
        .order-detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            padding-top: 0.5rem;
        }

        
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--radius-sharp);
            overflow: hidden;
            border: 1px solid var(--border-color-light);
        }

        .items-table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.9rem 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: left;
        }

        .items-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: var(--bg-subtle);
        }

        .items-table tbody tr:hover {
            background-color: var(--border-color-main);
        }

        .items-table tbody td {
            padding: 0.8rem 1rem;
            border-top: 1px solid var(--border-color-light);
            color: var(--text-color-medium);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .items-table td:last-child,
        .items-table th:last-child {
            text-align: right;
        }

        .items-table td:nth-child(3),
        .items-table th:nth-child(3) {
            text-align: center;
        }

        
        .total-summary-box {
            max-width: none;
            margin: 0;
            padding: 1.5rem;
            box-shadow: none;
            background-color: var(--bg-page);
            border: 1px solid var(--primary-color-light);
            border-radius: var(--radius-sharp);
            position: sticky;
            top: 20px;
            z-index: 5;
        }

        .summary-row {
            padding: 0.5rem 0;
            display: flex;
            justify-content: space-between;
        }

        .summary-label {
            font-weight: 600;
        }

        .summary-row.grand-total {
            border-top: 3px solid var(--primary-color);
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .summary-row.grand-total .summary-label {
            font-size: 1.15rem;
            color: var(--primary-color-dark);
        }

        .summary-row.grand-total .summary-value {
            color: var(--danger-color);
            font-size: 1.6rem;
            font-weight: 900;
        }

        
        .summary-row.change-amount {
            background-color: rgba(23, 162, 184, 0.1);
            border: 1px solid rgba(23, 162, 184, 0.3);
            border-radius: var(--radius-sharp);
            margin-top: 0.75rem;
            padding: 0.75rem 1rem;
        }

        .summary-row.change-amount .summary-value,
        .summary-row.change-amount .summary-label {
            color: var(--info-color);
            font-weight: 800;
        }

       
        @media (max-width: 992px) {
            .header-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .global-summary-row {
                grid-template-columns: 1fr;
            }

            .order-detail-grid {
                grid-template-columns: 1fr;
            }

            .total-summary-box {
                position: static;
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .page-header-container {
                padding: 1.5rem;
            }

            .summary-card .value {
                font-size: 2rem;
            }

            .accordion-toggle {
                padding: 1rem 1.2rem;
                font-size: 1rem;
                flex-direction: column;
                align-items: flex-start;
            }

            .toggle-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }

            .toggle-icon {
                display: none;
            }

            .items-table th:nth-child(3),
            .items-table td:nth-child(3) {
                display: none;
            }
        }
    </style>

    <div class="container">
        <div class="page-header-container">
            <div class="header-grid">
                <div>
                    <h1 class="page-title">
                        Detail Laporan Transaksi
                    </h1>
                    <p>Ringkasan transaksi untuk kasir terpilih pada periode ini.</p>
                </div>
                <div class="page-meta-info">
                    <div class="page-meta-item">
                        <i data-feather="user"></i>
                        Kasir: <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="page-meta-item">
                        <i data-feather="calendar"></i>
                        Periode: <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('F Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="alert-info-box mt-4">
                <i data-feather="info"></i>
                Tidak ditemukan data transaksi untuk kasir ini pada periode yang dipilih.
            </div>
        @else
            @php
                $totalTransactions = $orders->count();
                $totalRevenue = $orders->sum('jumlah_bayar');
            @endphp

            <div class="global-summary-row">
                <div class="summary-card">
                    <div class="label"><i data-feather="repeat"></i> Total Transaksi Tercatat</div>
                    <div class="value">{{ number_format($totalTransactions, 0, ',', '.') }}</div>
                </div>
                <div class="summary-card total-sales">
                    <div class="label"><i data-feather="dollar-sign"></i> Total Pendapatan</div>
                    <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>

            <h2 class="orders-list-header">Daftar Transaksi ({{ $totalTransactions }} Order)</h2>

            <div id="orders-accordion-list">
                @foreach($orders as $index => $order)
                    @php
                        $totalSubtotal = $order->detailOrders->sum('subtotal');
                        $paymentMethod = $order->payment_method ?? 'CASH';
                        $orderNumber = $index + 1;
                        $accordionId = 'order-collapse-' . $order->id;
                    @endphp

                    <div class="order-accordion">

                        <button class="accordion-toggle" type="button" aria-expanded="false" aria-controls="{{ $accordionId }}">

                            <div class="order-id-display">
                                Transaksi #{{ $orderNumber }}
                                <span class="badge payment-badge {{ strtolower($paymentMethod) == 'cash' ? 'cash' : 'non-cash' }}">
                                    {{ strtoupper($paymentMethod) }}
                                </span>
                            </div>

                            <div class="toggle-meta">
                                <div class="toggle-meta-item">
                                    Total Bayar:
                                    <strong>Rp {{ number_format($order->jumlah_bayar, 0, ',', '.') }}</strong>
                                </div>
                                <div class="toggle-meta-item">
                                    Waktu:
                                    <strong>{{ $order->created_at->format('H:i') }}</strong>
                                </div>
                                <span class="toggle-icon"><i data-feather="chevron-down"></i></span>
                            </div>
                        </button>

                        <div id="{{ $accordionId }}" class="accordion-content">
                            <div class="order-detail-grid">

                                <div class="order-items-section">
                                    <h5 style="margin-bottom: 1rem; font-weight: 600;">Detail Item Transaksi</h5>
                                    <table class="items-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">No.</th>
                                                <th style="width: 35%;">Menu</th>
                                                <th style="width: 10%;">Qty</th>
                                                <th style="width: 30%;">Harga Satuan</th>
                                                <th style="width: 20%; text-align: right;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->detailOrders as $itemIndex => $detail)
                                                @php
                                                    $hargaTransaksiPerUnit = $detail->harga_menu ?? 0;
                                                    $hargaNormalPerUnit = $detail->menu->harga ?? $hargaTransaksiPerUnit;
                                                    $diskonPersen = 0;
                                                    $isPromo = false;
                                                    if ($hargaNormalPerUnit > 0 && $hargaTransaksiPerUnit < $hargaNormalPerUnit) {
                                                        $diskonAbsolut = $hargaNormalPerUnit - $hargaTransaksiPerUnit;
                                                        $diskonPersen = round(($diskonAbsolut / $hargaNormalPerUnit) * 100);
                                                        $isPromo = true;
                                                    }

                                                    $subtotalDisplay = ($hargaTransaksiPerUnit * $detail->jumlah);
                                                @endphp
                                                <tr>
                                                    <td>{{ $itemIndex + 1 }}</td>
                                                    <td>{{ $detail->menu->nama_menu ?? 'Menu Dihapus' }}</td>
                                                    <td style="text-align: center;">{{ $detail->jumlah }}</td>
                                                    <td>
                                                        @if($isPromo)
                                                            <div
                                                                style="text-decoration: line-through; color: var(--text-color-light); font-size: 0.85rem; line-height: 1;">
                                                                Rp {{ number_format($hargaNormalPerUnit, 0, ',', '.') }}
                                                            </div>
                                                            <div
                                                                style="font-weight: 600; color: var(--danger-color); display: flex; align-items: center; gap: 5px; line-height: 1.3;">
                                                                Rp {{ number_format($hargaTransaksiPerUnit, 0, ',', '.') }}
                                                                <span
                                                                    style="font-size: 0.7rem; font-weight: 700; color: white; background-color: var(--danger-color); padding: 1px 4px; border-radius: 3px;">
                                                                    -{{ $diskonPersen }}%
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div style="font-weight: 600;">
                                                                Rp {{ number_format($hargaNormalPerUnit, 0, ',', '.') }}
                                                            </div>
                                                        @endif
                                                    </td>


                                                    <td style="text-align: right; font-weight: 700;">
                                                        Rp {{ number_format($subtotalDisplay, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="order-summary-section">
                                    <div class="total-summary-box">
                                        <h5 style="margin-bottom: 1rem; font-weight: 600;">Ringkasan Pembayaran</h5>

                                        @php
                                            $totalItemsSubtotal = $order->detailOrders->sum(fn($d) => ($d->harga_menu ?? 0) * $d->jumlah);
                                            $totalBayar = $order->jumlah_bayar;
                                            $diskonGlobal = $totalItemsSubtotal - $totalBayar;
                                        @endphp

                                        <div class="summary-row">
                                            <span class="summary-label">Subtotal Item:</span>
                                            <span class="summary-value">Rp
                                                {{ number_format($totalItemsSubtotal, 0, ',', '.') }}</span>
                                        </div>

                                        @if($diskonGlobal > 0)
                                            <div class="summary-row" style="color: var(--danger-color);">
                                                <span class="summary-label">Diskon Total Order:</span>
                                                <span class="summary-value">- Rp {{ number_format($diskonGlobal, 0, ',', '.') }}</span>
                                            </div>
                                        @endif

                                        <div class="summary-row grand-total">
                                            <span class="summary-label">TOTAL AKHIR:</span>
                                            <span class="summary-value">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                                        </div>

                                        <hr style="margin: 1.5rem 0; border-top: 1px solid var(--border-color-main);">

                                        <div class="summary-row">
                                            <span class="summary-label">Pembayaran:</span>
                                            <span class="summary-value">Rp
                                                {{ number_format($order->pembayaran ?? 0, 0, ',', '.') }}</span>
                                        </div>

                                        <div class="summary-row change-amount">
                                            <span class="summary-label">Kembalian:</span>
                                            <span class="summary-value">Rp
                                                {{ number_format($order->kembalian ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $startDate = request('start_date', \Carbon\Carbon::parse($tanggal)->startOfMonth()->format('Y-m-d'));
            $endDate = request('end_date', \Carbon\Carbon::parse($tanggal)->endOfMonth()->format('Y-m-d'));
        @endphp

        <div class="text-center mt-5">
            <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                class="btn btn-bottom-return">
                <i data-feather="arrow-left"></i> Kembali ke Daftar Laporan
            </a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (window.feather) {
                feather.replace();
            }

            const toggles = document.querySelectorAll('.accordion-toggle');

            toggles.forEach(toggle => {

                toggle.addEventListener('click', function () {
                    const targetId = this.getAttribute('aria-controls');
                    const targetContent = document.getElementById(targetId);
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    // --- Tutup semua yang terbuka ---
                    document.querySelectorAll('.accordion-content.expanded').forEach(content => {
                        if (content !== targetContent) {
                            content.classList.remove('expanded');
                            content.style.maxHeight = 0;
                            const siblingToggle = document.querySelector(`[aria-controls="${content.id}"]`);
                            if (siblingToggle) siblingToggle.setAttribute('aria-expanded', 'false');
                        }
                    });

                    if (!isExpanded) {
                        targetContent.style.maxHeight = 'none';

                        const detailGrid = targetContent.querySelector('.order-detail-grid');
                        const scrollHeight = detailGrid ? detailGrid.scrollHeight : 0;

                        targetContent.style.maxHeight = '0px';

                        requestAnimationFrame(() => {
                            targetContent.classList.add('expanded');
                            targetContent.style.maxHeight = (scrollHeight + 50) + "px";
                            this.setAttribute('aria-expanded', 'true');
                        });

                    } else {
                        targetContent.classList.remove('expanded');
                        targetContent.style.maxHeight = 0;
                        this.setAttribute('aria-expanded', 'false');
                    }
                });
            });
        });
    </script>
@endsection