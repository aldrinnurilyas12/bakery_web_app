<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\FuncCall;

class ShoppingCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add(Request $request)
    {
        $cart = Session::get('cart', []);

$cart_product = [
    'product'       => $request->product,
    'variant'       => $request->variant,
    'variant_type'  => $request->variant_type,
    'product_name'  => $request->product_name,
    'price'         => $request->price,
    'quantity'      => $request->quantity,
];

$found = false;

foreach ($cart as &$item) {

    // Jika produk punya variant
    if (!empty($cart_product['variant'])) {

        if (
            $item['product'] === $cart_product['product'] &&
            $item['variant'] === $cart_product['variant'] &&
            $item['variant_type'] === $cart_product['variant_type']
        ) {
            $item['quantity'] += $cart_product['quantity'];
            $found = true;
            break;
        }

    } else {

        // Produk tanpa variant
        if ($item['product'] === $cart_product['product']) {
            $item['quantity'] += $cart_product['quantity'];
            $found = true;
            break;
        }
    }
}

// Jika tidak ditemukan → push item baru
if (!$found) {
    $cart[] = $cart_product;
}

Session::put('cart', $cart);

        session()->flash('add_cart_success', 'Produk berhasil ditambahkan!');
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function clear_cart_session(): RedirectResponse
    {

        Session::forget('cart');
        session()->flash('success_empty_cart', 'Keranjang berhasil dikosongkan!');
        return redirect()->route('transaction_create');
    }
    

    public function delete_cart_product(Request $request): RedirectResponse
    {
        // Retrieve the cart from the session
        $cart = Session::get('cart', []);

        // Get the product ID to delete from the request
        $prdCode = $request->product_code;

        // Check if the cart is not empty and the product ID exists
        if ($prdCode && !empty($cart)) {
            // Loop through the cart to find the item with the matching ID and remove it
            foreach ($cart as $key => $cartItem) {
                if ($cartItem['product'] == $prdCode) {
                    unset($cart[$key]); // Remove the product from the cart
                    break;
                }
            }

            // Update the session with the new cart data
            Session::put('cart', $cart);
        }

         session()->flash('success_empty_cart', 'Berhasil hapus produk!');
        return redirect()->back();
    }
}