@extends('account.sidenav')
@section('sidenav')
<change-product :product="{{$product}}" :storage="{{json_encode(asset('storage/products/'))}}"></change-product>
@endsection
