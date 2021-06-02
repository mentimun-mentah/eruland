@extends('account.sidenav')
@section('sidenav')
<account-orders-incoming :product="{{json_encode(asset('storage/products/'))}}" :storage="{{json_encode(asset('storage/bank-logo/'))}}"></account-orders-incoming>
@endsection
