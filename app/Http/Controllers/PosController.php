<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $transaction = Transaction::create([
                'invoice' => 'INV' . time(),
                'total' => $request->total,
                'pay' => $request->pay,
                'change' => $request->change,
            ]);

            foreach ($request->items as $item) {

                $product = Product::findOrFail($item['id']);

                // Cek stok cukup atau tidak
                if ($product->stock < $item['qty']) {
                    return response()->json([
                        'error' => 'Stok tidak cukup untuk ' . $product->name
                    ], 400);
                }

                // Simpan item transaksi
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'qty' => $item['qty'],
                    'subtotal' => $product->price * $item['qty'],
                ]);

                // Kurangi stok
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function receipt($id)
    {
        $transaction = Transaction::with('items')->findOrFail($id);
        return view('pos.receipt_print', compact('transaction'));
    }
}
