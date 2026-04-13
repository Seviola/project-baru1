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
        DB::beginTransaction();

        try {

            $transaction = Transaction::create([
                'user_id' => auth()->id(), //kasir yang login
                'invoice' => 'INV' . time(),
                'total' => $request->total,
                'pay' => $request->pay,
                'change' => $request->change,
                'is_deposited' => 0
            ]);

            foreach ($request->items as $item) {

                $product = Product::find($item['id']);

                if (!$product) {
                    continue;
                }

                if ($product->stock < $item['qty']) {
                    return response()->json([
                        'error' => 'Stock tidak cukup untuk ' . $product->name
                    ]);
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_name' => $product->name,
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);

                $product->stock -= $item['qty'];
                $product->save();
            }

            DB::commit();

            return response()->json([
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function setor()
    {
        $userId = auth()->id();
        $today = Carbon::today();

        Transaction::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->where('is_deposited', 0)
            ->update([
                'is_deposited' => 1
            ]);
        return response()->json([
            'message' => 'Uang berhasil disetor!'
        ]);
    }
}
