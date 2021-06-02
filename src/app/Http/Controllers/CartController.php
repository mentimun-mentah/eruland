<?php

namespace App\Http\Controllers;

use Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function my_cart()
  {
    return view('carts.my_cart');
  }

  public function storeCart(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:products,id',
      'qty' => 'required|integer|min:1',
    ]);

    $product = Product::with('user')->findOrFail($request->id);

    if($request->qty > $product->stock) 
      return response()->json(['errors' => ['qty' => ['Cannot be greater than '.$product->stock]]],422);

    $duplicates = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($request) {
      return $cartItem->id === $request->id;
    });  

    if(!$duplicates->isEmpty())
      return response()->json(['errors' => ['qty' => ['Item already in cart']]],422);

    Cart::instance('cart')->add($product->id, $product->name, $request->qty, $product->price, 
      [
        'image' => $product->image,
        'stock' => $product->stock,
        'weight' => $product->weight,
        'slug' => $product->slug,
        'user_id' => $product->user_id,
        'shipping_subdistrict_id' => $product->user->shipping_subdistrict_id
      ]
    )->associate('App\Models\Product');

    toast('Success add product to cart','success');
    return ['status' => 'Success add product to cart'];
  }

  public function inputCart(Request $request, $id)
  {
    $request->validate([
      'qty' => 'required|integer|min:1'
    ]);

    $cart = Cart::instance('cart')->get($id);
    $product = Product::findOrFail($cart->id);

    if($request->qty > $product->stock) 
      return response()->json(['errors' => ['qty' => ['Cannot be greater than '.$product->stock]]],422);

    Cart::instance('cart')->update($id, $request->qty);

    return ['status' => 'Success update cart'];
  }

  public function increaseCart(Request $request, $id)
  {
    $request->validate([
      'qty' => 'required|integer|min:1'
    ]);

    $cart = Cart::instance('cart')->get($id);
    $product = Product::findOrFail($cart->id);

    $qty = $request->qty + 1;

    if($qty > $product->stock) 
      return response()->json(['errors' => ['qty' => ['Cannot be greater than '.$product->stock]]],422);

    Cart::instance('cart')->update($id, $qty);

    return ['status' => 'Success update cart'];
  }

  public function decreaseCart(Request $request, $id)
  {
    $request->validate([
      'qty' => 'required|integer|min:1'
    ]);

    $cart = Cart::instance('cart')->get($id);
    $product = Product::findOrFail($cart->id);

    $qty = $request->qty - 1;

    if($qty < 1){
      Cart::instance('cart')->remove($id);
      return ['status' => 'The item has been deleted'];
    }

    Cart::instance('cart')->update($id, $qty);

    return ['status' => 'Success update cart'];
  }

  public function deleteCart($id)
  {
    Cart::instance('cart')->remove($id);
    return ['status' => 'Success delete cart'];
  }

  public function deleteAllCart()
  {
    Cart::instance('cart')->destroy();
    return ['status' => 'Success delete all cart'];
  }

}
