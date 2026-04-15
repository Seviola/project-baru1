<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Restock {{ $batchNo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 30px 10px;
        }
        .invoice-wrapper {
            background: white; width: 780px; padding: 40px;
            border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .invoice-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #4680ff;
        }
        .store-name { font-size: 24px; font-weight: 700; color: #4680ff; }
        .store-info { font-size: 12px; color: #666; margin-top: 4px; line-height: 1.8; }
        .invoice-title h2 { font-size: 26px; font-weight: 800; color: #333; letter-spacing: 2px; text-align: right; }
        .invoice-title .invoice-no { font-size: 13px; color: #4680ff; font-weight: 600; text-align: right; margin-top: 4px; }

        .parties { display: flex; justify-content: space-between; margin-bottom: 24px; gap: 20px; }
        .party-box { flex: 1; background: #f8f9ff; border-radius: 8px; padding: 14px 18px; border-left: 4px solid #4680ff; }
        .party-box.right { border-left: none; border-right: 4px solid #28a745; }
        .party-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 6px; }
        .party-name { font-size: 15px; font-weight: 700; color: #333; margin-bottom: 4px; }
        .party-detail { font-size: 12px; color: #666; line-height: 1.7; }

        .invoice-info {
            display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;
            background: #f8f9ff; padding: 12px 18px; border-radius: 8px; margin-bottom: 24px;
        }
        .info-item label { font-size: 10px; color: #999; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 3px; }
        .info-item span { font-size: 13px; font-weight: 600; color: #333; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger  { background: #f8d7da; color: #721c24; }

        .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .invoice-table thead tr { background: #4680ff; color: white; }
        .invoice-table thead th { padding: 11px 12px; font-size: 12px; font-weight: 600; text-align: left; }
        .invoice-table thead th.text-right { text-align: right; }
        .invoice-table tbody tr { border-bottom: 1px solid #eee; }
        .invoice-table tbody tr:nth-child(even) { background: #fafafa; }
        .invoice-table tbody td { padding: 10px 12px; font-size: 13px; color: #444; }
        .invoice-table tbody td.text-right { text-align: right; }
        .invoice-table tfoot td { padding: 10px 12px; font-size: 13px; font-weight: 600; border-top: 2px solid #eee; }
        .invoice-table tfoot td.text-right { text-align: right; }
        .invoice-table tfoot tr.grand-total td { background: #4680ff; color: white; font-size: 14px; }

        .invoice-footer {
            margin-top: 30px; padding-top: 20px; border-top: 1px dashed #ddd;
            display: flex; justify-content: space-between; align-items: flex-end;
        }
        .footer-note { font-size: 12px; color: #888; line-height: 1.8; }
        .footer-note strong { color: #4680ff; display: block; margin-bottom: 4px; font-size: 13px; }
        .signature { text-align: center; font-size: 12px; color: #999; }
        .sign-line { width: 130px; border-top: 1px solid #aaa; margin: 50px auto 6px; }

        .print-actions { text-align: center; margin-top: 30px; }
        .btn-print { background: #4680ff; color: white; border: none; padding: 10px 28px; border-radius: 6px; font-size: 14px; cursor: pointer; margin: 0 5px; }
        .btn-back  { background: #6c757d; color: white; border: none; padding: 10px 28px; border-radius: 6px; font-size: 14px; cursor: pointer; margin: 0 5px; }

        @media print {
            body { background: white; padding: 0; }
            .invoice-wrapper { box-shadow: none; border-radius: 0; }
            .print-actions { display: none; }
        }
    </style>
</head>
<body>
@php
    $firstItem      = $items->first();
    $totalBeliAll   = $items->sum(fn($i) => ($i->product->purchase_price ?? 0) * $i->stock);
    $totalJualAll   = $items->sum(fn($i) => ($i->product->price ?? 0) * $i->stock);
@endphp

<div class="invoice-wrapper">

    {{-- HEADER --}}
    <div class="invoice-header">
        <div>
            <div class="store-name">TOKO Sevi & Kifli</div>
            <div class="store-info">Jl. Manyor No. 1<br>Telp: 08123456789</div>
        </div>
        <div class="invoice-title">
            <h2>INVOICE RESTOCK</h2>
            <div class="invoice-no">{{ $batchNo }}</div>
        </div>
    </div>

    {{-- VENDOR & TOKO --}}
    <div class="parties">
        <div class="party-box">
            <div class="party-label">Dari (Vendor)</div>
            <div class="party-name">{{ $vendor->name ?? '-' }}</div>
            <div class="party-detail">
                {{ $vendor->phone ?? '-' }}<br>
                {{ $vendor->email ?? '-' }}<br>
                {{ $vendor->address ?? '-' }}
            </div>
        </div>
        <div class="party-box right">
            <div class="party-label">Kepada (Toko)</div>
            <div class="party-name">TOKO Sevi & Kifli</div>
            <div class="party-detail">Jl. Manyor No. 1<br>Telp: 08123456789</div>
        </div>
    </div>

    {{-- INFO --}}
    <div class="invoice-info">
        <div class="info-item">
            <label>Tanggal Kirim</label>
            <span>{{ $firstItem->created_at->format('d/m/Y') }}</span>
        </div>
        <div class="info-item">
            <label>Waktu</label>
            <span>{{ $firstItem->created_at->format('H:i') }} WIB</span>
        </div>
        <div class="info-item">
            <label>Total Jenis Produk</label>
            <span>{{ $items->count() }} produk</span>
        </div>
        <div class="info-item">
            <label>Status Restock</label>
            <span>
                @php $statuses = $items->pluck('status')->unique(); @endphp
                @if($statuses->contains('approved') && !$statuses->contains('pending'))
                    <span class="badge badge-success">Disetujui</span>
                @elseif($statuses->contains('rejected') && !$statuses->contains('pending'))
                    <span class="badge badge-danger">Ditolak</span>
                @else
                    <span class="badge badge-warning">Pending</span>
                @endif
            </span>
        </div>
        <div class="info-item">
            <label>Status Bayar</label>
            <span>
                @if($items->every(fn($i) => $i->payment_status === 'paid'))
                    <span class="badge badge-success">Lunas</span>
                @else
                    <span class="badge badge-warning">Belum Bayar</span>
                @endif
            </span>
        </div>
    </div>

    {{-- TABLE --}}
    <table class="invoice-table">
        <thead>
            <tr>
                <th width="35">#</th>
                <th>Nama Produk</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total Beli</th>
                <th class="text-right">Total Jual</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            @php
                $hargaBeli  = $item->product->purchase_price ?? 0;
                $hargaJual  = $item->product->price ?? 0;
                $qty        = $item->stock;
                $totalBeli  = $hargaBeli * $qty;
                $totalJual  = $hargaJual * $qty;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($hargaBeli, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                <td class="text-right">{{ $qty }} unit</td>
                <td class="text-right">Rp {{ number_format($totalBeli, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalJual, 0, ',', '.') }}</td>
                <td>
                    @if($item->status === 'approved')
                        <span class="badge badge-success">Disetujui</span>
                    @elseif($item->status === 'rejected')
                        <span class="badge badge-danger">Ditolak</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">Subtotal</td>
                <td class="text-right">Rp {{ number_format($totalBeliAll, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalJualAll, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr class="grand-total">
                <td colspan="5" class="text-right">Total Keuntungan</td>
                <td colspan="2" class="text-right">
                    Rp {{ number_format($totalJualAll - $totalBeliAll, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER --}}
    <div class="invoice-footer">
        <div class="footer-note">
            <strong>Catatan</strong>
            Invoice ini merupakan bukti pengiriman stok dari vendor.<br>
            Harap simpan sebagai arsip transaksi restock.
            @if($firstItem->paid_at)
            <br>Dibayar pada: {{ $firstItem->paid_at->format('d/m/Y H:i') }}
            @endif
        </div>
        <div class="signature">
            <div class="sign-line"></div>
            Petugas Gudang
        </div>
    </div>

</div>

<div class="print-actions">
    <button class="btn-print" onclick="window.print()">🖨️ Print Invoice</button>
    <button class="btn-back" onclick="history.back()">← Kembali</button>
</div>

<script>
    window.onload = function() { window.print(); };
    window.onafterprint = function() { history.back(); };
</script>
</body>
</html>