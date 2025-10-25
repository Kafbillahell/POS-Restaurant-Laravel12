@extends('dashboard.home')

@section('styles')
    <style>
        .struk-container {
            max-width: 320px;
            margin: 1.5rem auto;
            background-color: #fff;
            border: none;
            border-radius: 0;
            box-shadow: none;
            padding: 1rem 1.25rem;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            font-size: 12px;
            line-height: 1.3;
            letter-spacing: 0.03em;
        }

        .struk-header {
            text-align: center;
            margin-bottom: 1rem;
        }

        .struk-header img {
            width: 40px;
            height: auto;
            margin-bottom: 0.25rem;
        }

        .menu-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 1rem;
        }

        .menu-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
            border-bottom: 1px dashed #aaa;
            word-break: break-word;
        }

        .menu-list li span {
            white-space: nowrap;
            min-width: 70px;
            text-align: right;
        }

        .total-line {
            border-top: 1px solid #000;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }

        .total-line p {
            margin: 0.2rem 0;
            display: flex;
            justify-content: space-between;
        }

        .text-center {
            text-align: center;
            margin: 0.5rem 0;
            font-weight: 600;
        }

        .footer-note {
            font-size: 10px;
            margin-top: 1rem;
            text-align: center;
        }

        .btn-back,
        .btn-print {
            position: fixed;
            bottom: 20px;
            z-index: 1000;
            border-radius: 50px;
            padding: 0.4rem 1.25rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
        }

        .btn-back {
            right: 20px;
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #0b5ed7;
            color: white;
        }

        .btn-print {
            right: 120px;
            background-color: #0d6efd;
            color: white;
            border: none;
        }

        .btn-print:hover {
            background-color: #0b5ed7;
        }


        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            .struk-container {
                position: absolute;
                left: 50%;
                top: 0;
                transform: translateX(-50%);
                width: 320px;
                max-width: none;
                margin: 0;
                padding: 0.5rem 1rem;
                box-shadow: none;
                border: none;
                background: white;
                font-size: 12.5px;
                line-height: 1.4;
                letter-spacing: 0.02em;
                font-family: 'Courier New', Courier, monospace;
                color: #000;
                zoom: 1.5;
            }

            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }

            body * {
                visibility: hidden;
            }

            .struk-container,
            .struk-container * {
                visibility: visible;
            }

            .btn-back,
            .btn-print {
                display: none !important;
            }
        }
    </style>
@endsection


@section('content')
    <div class="struk-container">
        <div class="struk-header">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Logo Perusahaan">
            <div><strong>KAFFE</strong></div>
            <div>Jl. Juanda no.17, Cianjur</div>
            <div>Telp: 0895-0768-6298</div>
        </div>

        <div class="text-center">
            ==============================
            <div><strong>Struk Pembayaran</strong></div>
            ==============================
        </div>

        <p><strong>No. Order:</strong> #{{ $order->id ?? '-' }}</p>
        <p><strong>Nama Pemesan:</strong> {{ $order->nama_pemesan ?? '-' }}</p>
        <p><strong>Nama Kasir:</strong> {{ $order->nama_kasir ?? '-' }}</p>
        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') ?? '-' }}</p>

        <hr>

        <ul class="menu-list">
            @foreach ($order->detailOrders()->withTrashed()->get() as $detailOrder)
                @php

                    $harga_transaksi = $detailOrder->harga_menu ?? 0;

                    $harga_normal_item = $detailOrder->menu->harga ?? $harga_transaksi;

                    $diskon_persen = 0;
                    if ($harga_normal_item > 0 && $harga_transaksi < $harga_normal_item) {

                        $diskon_persen = round((($harga_normal_item - $harga_transaksi) / $harga_normal_item) * 100);
                    }
                @endphp

                <li @if($detailOrder->trashed()) style="text-decoration: line-through; color: red;" @endif>

                    <div style="flex-grow: 1; word-break: break-word;">
                        {{ $detailOrder->nama_menu ?? '-' }} x {{ $detailOrder->jumlah }}

                        @if ($diskon_persen > 0)
                            <span style="color: green; font-size: 10px; font-weight: bold; margin-left: 5px;">
                                (-{{ $diskon_persen }}%)
                            </span>
                        @endif
                    </div>

                    <span>
                        Rp{{ number_format(($detailOrder->harga_menu ?? 0) * $detailOrder->jumlah, 0, ',', '.') }}
                    </span>
                </li>
            @endforeach
        </ul>

        <div class="total-line">
            <p><strong>Subtotal:</strong>
                <span>Rp{{ number_format($order->detailOrders->sum(fn($d) => ($d->harga_menu ?? 0) * $d->jumlah), 0, ',', '.') }}</span>
            </p>
            <p><strong>Jumlah Bayar:</strong>
                <span>Rp{{ number_format($order->jumlah_bayar ?? 0, 0, ',', '.') }}</span>
            </p>
            <p><strong>Kembalian:</strong>
                <span>Rp{{ number_format($order->kembalian ?? 0, 0, ',', '.') }}</span>
            </p>
        </div>

        <hr>

        <div class="text-center mb-2">
            <img src="{{ url('/barcode/' . $order->id) }}" alt="Barcode">
        </div>

        <div class="text-center">
            ***** TERIMA KASIH *****
        </div>

        <p class="footer-note text-center">Simpan struk ini sebagai bukti pembayaran</p>
    </div>

    <button class="btn btn-print" onclick="window.print()">Cetak Struk</button>

    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-back">Kembali</a>
@endsection