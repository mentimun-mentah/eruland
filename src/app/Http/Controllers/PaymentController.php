<?php

namespace App\Http\Controllers;

use Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct(){                                                                               
      // Set your Merchant Server Key
      \Midtrans\Config::$serverKey = env('MIDTRANS_SERVERKEY');
      // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
      \Midtrans\Config::$isProduction = false;
      // Set sanitization on (default)
      \Midtrans\Config::$isSanitized = true;
      // Set 3DS transaction for credit card to true
      \Midtrans\Config::$is3ds = true;
    }

    public function index()
    {
      return view('payments.index');
    }

    public function getSnapToken(Request $request)
    {
      $request->validate([
        'fullname' => 'required|string|min:6|max:255',
        'phone' => 'required|min:10|max:20|regex:/(08)[0-9]{9}/',
        'address' => 'required|string|min:6',
        'code_pos' => 'required|integer|min:1',
        'shipping_subdistrict_id' => 'required|exists:shipping_subdistricts,id'
      ]);

      $user = User::findOrFail(Auth::user()->id);
      $user->fullname = $request->fullname;
      $user->phone = $request->phone;
      $user->address = $request->address;
      $user->code_pos = $request->code_pos;
      $user->shipping_subdistrict_id = $request->shipping_subdistrict_id;
      $user->save();

      $items = array();

      foreach(Cart::instance('cart')->content() as $item){
        $items[] = array(
          'id' => $item->rowId,
          'price' => $item->price,
          'quantity' => $item->qty,
          'name' => $item->name
         );
      }

      $items[] = array(
          'id' => strtoupper(uniqid()),
          'price' => $request->total_cost_shipping,
          'quantity' => 1,
          'name' => 'Total Pengiriman'
      );

      $payload = [
        'transaction_details' => [
            'order_id'      => 'ERULAND-'.strtoupper(uniqid()).'-INV',
        ],
        'customer_details' => [
            'first_name'    => $request->fullname,
            'email'         => Auth::user()->email,
            'phone'         => $request->phone,
        ],
        'item_details' => $items
      ];

    try{
      $snapToken = \Midtrans\Snap::getSnapToken($payload);
      //return redirect($vtweb_url);
      $this->response['snap_token'] = $snapToken;
      return response()->json($this->response);
    }catch (Exception $e){
      return $e->getMessage;
    }
  }

  public function saveOrder(Request $request)
  {

    $req_order = $request->order;
    $req_items = $request->items;

    $order = Order::create([
      'bank' => $req_order['bank'],
      'gross_amount' => $req_order['gross_amount'],
      'total_cost_shipping' => $req_order['total_cost_shipping'],
      'order_id' => $req_order['order_id'],
      'payment_type' => $req_order['payment_type'],
      'pdf_url' => $req_order['pdf_url'],
      'transaction_status' => $req_order['transaction_status'],
      'va_number' => $req_order['va_number'],
      'user_id' => Auth::user()->id
    ]);  

    foreach($req_items as $row){
      OrderItem::create([
        'qty' => $row['qty'],
        'price' => $row['price'],
        'code_shipping' => $row['code_shipping'],
        'service_shipping' => $row['service_shipping'],
        'cost_shipping' => $row['cost_shipping'],
        'etd_shipping' => $row['etd_shipping'],
        'order_id' => $order->id,
        'penjual_id' => $row['penjual_id'],
        'product_id' => $row['product_id']
      ]);
    }

    Cart::instance('cart')->destroy();

    return ['status' => 'Success save order'];
  }

  public function decreaseStock($id){
    $results = DB::table('order_items')->where('order_items.order_id','=',$id)->get();

    foreach($results as $row){
      $product = Product::findOrFail($row->product_id);
      $product->stock = $product->stock - 1;
      $product->save();
    }
  }

  public function notificationHandler(Request $request)
  {
    $notif = new \Midtrans\Notification();

    $transaction = $notif->transaction_status;
    $type = $notif->payment_type;
    $order_id = $notif->order_id;
    $fraud = $notif->fraud_status;
    $order = Order::where('order_id',$order_id)->first();

    if ($transaction == 'capture') {
        // For credit card transaction, we need to check whether transaction is challenge by FDS or not
        if ($type == 'credit_card') {
            if ($fraud == 'challenge') {
                // TODO set payment status in merchant's database to 'Challenge by FDS'
                // TODO merchant should decide whether this transaction is authorized or not in MAP
                // echo "Transaction order_id: " . $order_id ." is challenged by FDS";
              $order->setPending();                                                                                    
            } else {
                // TODO set payment status in merchant's database to 'Success'
                // echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
              $order->setSuccess();                                                                                    
              $this->decreaseStock($order->id);
            }
        }
    } else if ($transaction == 'settlement') {
        // TODO set payment status in merchant's database to 'Settlement'
        //echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
        $order->setSuccess();
        $this->decreaseStock($order->id);
    } else if ($transaction == 'pending') {
        // TODO set payment status in merchant's database to 'Pending'
        // echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
        $order->setPending();
    } else if ($transaction == 'deny') {
        // TODO set payment status in merchant's database to 'Denied'
        // echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
        $order->setFailed();
    } else if ($transaction == 'expire') {
        // TODO set payment status in merchant's database to 'expire'
        // echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
        $order->setExpired();
    } else if ($transaction == 'cancel') {
        // TODO set payment status in merchant's database to 'Denied'
        // echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
        $order->setFailed();
    }

  }

  public function cancelOrder($order_id)
  {
    \Midtrans\Transaction::cancel($order_id);
    return ['status' => 'Success cancel order'];
  }
}
