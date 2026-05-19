<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $userId = Auth::id();
        $today = Carbon::today();

        // Total hari ini
        $totalToday = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('total');

        // Sudah disetor
        $alreadyDeposited = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->where('is_deposited', 1)
            ->sum('total');

        // Belum disetor
        $notDeposited = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->where('is_deposited', 0)
            ->sum('total');

        $lastTransaction = Transaction::latest()->first(); /*invoice tidak riset 1 tahun atau 1 bulan sekali kwitansi*/
        $nextNumber = 1;

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->invoice, 0, 3);
            $nextNumber = $lastNumber + 1;
        }

        $invoiceNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $romanMonths = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        $monthRoman = $romanMonths[now()->month];

        $nextInvoice = $invoiceNumber . '/' . $monthRoman . '/EDU/' . now()->year;

        return view('pos.index', compact(
            'products',
            'totalToday',
            'alreadyDeposited',
            'notDeposited',
            'nextInvoice'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'barcode' => $request->barcode,
            'name' => $request->name,
            'vendor_id' => $request->vendor_id,
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'stock' => 0,
            'description' => $request->description,
            'image' => $data['image'] ?? null
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function receipt($id)
    {
        $transaction = Transaction::with('items','user')->findOrFail($id);

        // Selain admin hanya bisa lihat kwitansi miliknya
        if (auth()->user()->role != 'admin' &&
            $transaction->user_id != auth()->id()) {
                abort(403, 'Akses ditolak');
        }

        return view('pos.receipt_print', compact('transaction'));
    }

    public function checkout(Request $request)
    {
        try {

            $request->validate([
                'student_name' => 'required',
                'payment_method' => 'required',
                'total' => 'required|numeric',
                'pay' => 'required|numeric',
                'change' => 'required|numeric',
                'items' => 'required|array'
            ]);

            $lastTransaction = Transaction::latest()->first(); /*invoice tidak riset 1 tahun atau 1 bulan sekali tampilan POS*/
            $nextNumber = 1;
            if ($lastTransaction) {
                // Ambil nomor depan invoice lama
                $lastNumber = (int) substr($lastTransaction->invoice, 0, 3);
                $nextNumber = $lastNumber + 1;
            }

            // Format 001, 002, dst
            $invoiceNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Bulan romawi
            $romanMonths = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII'
            ];

            $monthRoman = $romanMonths[now()->month];

            // Hasil akhir invoice
            $invoiceCode = $invoiceNumber . '/' . $monthRoman . '/EDU/' . now()->year;

            // SIMPAN TRANSAKSI//
            $transaction = Transaction::create([
                'invoice' => $invoiceCode,
                'student_name' => $request->student_name,
                'payment_method' => $request->payment_method,
                'total' => $request->total,
                'pay' => $request->pay,
                'change' => $request->change,
                'user_id' => auth()->id(),
                'is_deposited' => 0
            ]);

            foreach($request->items as $item){

                $product = Product::find($item['id']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'class_type' => $product ? $product->class_type : '-',
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);
            }

            return response()->json([
                'transaction_id' => $transaction->id,
                'invoice' => $transaction->invoice
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public static function terbilang($angka)
    {
        $angka = abs($angka);

        $baca = [
            "", "satu", "dua", "tiga", "empat",
            "lima", "enam", "tujuh", "delapan",
            "sembilan", "sepuluh", "sebelas"
        ];

        if ($angka < 12)
            return " " . $baca[$angka];
        elseif ($angka < 20)
            return self::terbilang($angka - 10) . " belas";
        elseif ($angka < 100)
            return self::terbilang($angka / 10) . " puluh" . self::terbilang($angka % 10);
        elseif ($angka < 200)
            return " seratus" . self::terbilang($angka - 100);
        elseif ($angka < 1000)
            return self::terbilang($angka / 100) . " ratus" . self::terbilang($angka % 100);
        elseif ($angka < 2000)
            return " seribu" . self::terbilang($angka - 1000);
        elseif ($angka < 1000000)
            return self::terbilang($angka / 1000) . " ribu" . self::terbilang($angka % 1000);
        elseif ($angka < 1000000000)
            return self::terbilang($angka / 1000000) . " juta" . self::terbilang($angka % 1000000);
    }

    public function setor()
    {
        \App\Models\Transaction::where('user_id', auth()->id())
            ->whereDate('created_at', now())
            ->where('is_deposited', 0)
            ->update(['is_deposited' => 1]);

        return response()->json([
            'message' => 'Setoran berhasil yee'
        ]);
    }

    public function dailyReport()
    {
        if (auth()->user()->role == 'admin') {
            // Admin bisa lihat semua transaksi
            $transactions = Transaction::with(['items.product','user'])
                ->latest()
                ->get();
        } else {
            // Kasir hanya lihat transaksi miliknya
            $transactions = Transaction::with(['items.product','user'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();
        }
        
        return view('reports.daily', compact('transactions'));
    }

    public function receiptPdf($id)
    {
        $transaction = Transaction::with('items','user')->findOrFail($id);

        if (auth()->user()->role != 'admin' && 
            $transaction->user_id != auth()->id()) {
                abort(403, 'Akses ditolak');
        }

        $pdf = Pdf::loadView('pos.receipt_pdf', compact('transaction'))
                    ->setPaper('a5', 'portrait');
        $nama = preg_replace('/[^A-Za-z0-9\-]/', '-', $transaction->student_name);

        return $pdf->download('Kwitansi-'.$nama.'.pdf');
    }
}
