<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{

  public function transaction()
  {
    return view('admin.transaction');
  }

  public function getTransactionPenjual(Request $request)
  {
    $results = DB::table('users')
      ->where('users.role','=','penjual')
      ->where('users.status','=',1)
      ->select(
        'users.id as users_id', 'users.name as users_name', 'users.email as users_email', 'users.ktp as users_ktp', 'users.role as users_role',
        'users.status as users_status', 'users.address as users_address', 'users.no_rek as users_no_rek', 'users.a_n as users_a_n',
        'users.code_pos as users_code_pos', 'users.shipping_subdistrict_id as users_shipping_subdistrict_id'
      )
      ->paginate(6);

    foreach($results as $data){
      $balance = DB::table('withdrawals')
        ->where('withdrawals.user_id','=',$data->users_id)
        ->where('withdrawals.status','=','pending')
        ->select(
          DB::raw('SUM(withdrawals.nominal) as nominal_sum')
        )
        ->pluck('nominal_sum');

      $data->balance = $balance[0];

      $withdrawals = DB::table('withdrawals')
        ->where('withdrawals.user_id','=',$data->users_id)
        ->orderBy('withdrawals.id','desc')
        ->select(
          'withdrawals.id as withdrawals_id', 'withdrawals.nominal as withdrawals_nominal', 'withdrawals.status as withdrawals_status',
          'withdrawals.user_id as withdrawals_user_id', 'withdrawals.created_at as withdrawals_created_at',
          'withdrawals.updated_at as withdrawals_updated_at'
        )
        ->get();

      $data->withdrawals = $withdrawals;
    }

    return $results;
  }

  public function setWithdrawalsSuccess(Request $request, $id)
  {
    $withdrawals = Withdrawal::findOrFail($id);
    $withdrawals->status = 'success';
    $withdrawals->touch();
    $withdrawals->save();

    return ['status' => 'Success change withdrawals to success'];
  }

}
