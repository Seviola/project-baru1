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
        $products = Product::orderBy('name')->get();
        return view('pos.index', compact('products'));
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
        $transaction = Transaction::with('items')->findOrFail($id);
        return view('pos.receipt_print', compact('transaction'));
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();

        try {

            $transaction = Transaction::create([
                'invoice' => 'INV' . time(),
                'total' => $request->total,
                'pay' => $request->pay,
                'change' => $request->change
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
}
