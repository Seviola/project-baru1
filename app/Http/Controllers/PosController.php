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

        return view('pos.index', compact(
            'products',
            'totalToday',
            'alreadyDeposited',
            'notDeposited'
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

            DB::beginTransaction();

            $transaction = Transaction::create([
                'invoice' => 'KW-' . date('YmdHis'),
                'student_name' => $request->student_name,
                'payment_method' => $request->payment_method,
                'total' => $request->total,
                'pay' => $request->pay,
                'change' => $request->change,
                'user_id' => auth()->id(),
                'is_deposited' => 0
            ]);

            foreach($request->items as $item){

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
<<<<<<< Updated upstream
                    'product_name' => $item['name'].' -'.$item['class_type'],
=======
                    'product_name' => $item['name'],
                    'class_type' => $item['class_type'],
>>>>>>> Stashed changes
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);
            }

            DB::commit();

            return response()->json([
                'transaction_id' => $transaction->id
            ]);

        } catch(\Exception $e){

            DB::rollback();

            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

        if ($angka < 12)
            return " " . $baca[$angka];
        elseif ($angka < 20)
            return $this->terbilang($angka - 10) . " belas";
        elseif ($angka < 100)
            return $this->terbilang($angka / 10) . " puluh" . $this->terbilang($angka % 10);
        elseif ($angka < 200)
            return " seratus" . $this->terbilang($angka - 100);
        elseif ($angka < 1000)
            return $this->terbilang($angka / 100) . " ratus" . $this->terbilang($angka % 100);
        elseif ($angka < 2000)
            return " seribu" . $this->terbilang($angka - 1000);
        elseif ($angka < 1000000)
            return $this->terbilang($angka / 1000) . " ribu" . $this->terbilang($angka % 1000);
        elseif ($angka < 1000000000)
            return $this->terbilang($angka / 1000000) . " juta" . $this->terbilang($angka % 1000000);
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
}
