@extends('account.sidenav')
@section('sidenav')
<my-product :home="{{json_encode(url('/'))}}" :storage="{{json_encode(asset('storage/'))}}"></my-product>
@endsection
